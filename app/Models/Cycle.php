<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cycle extends Model
{
    protected $fillable = [
        'shed_id',
        'start_date',
        'end_date',
        'initial_chicks',
        'floor_chicks',
        'mortality_count',
        'sold_chicks',
        'total_weight',
        'status'
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'floor_chicks' => 'array',  // JSON -> array
    ];

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function financialRecords(): HasMany
    {
        return $this->hasMany(FinancialRecord::class);
    }

    public function medicineDispensations(): HasMany
    {
        return $this->hasMany(MedicineDispensation::class);
    }

    public function mortalityRecords(): HasMany
    {
        return $this->hasMany(MortalityRecord::class);
    }

    public function dispensations(): HasMany
    {
        return $this->hasMany(CycleDispensation::class);
    }

    /**
     * الحصول على عدد الأدوار من العنبر
     */
    public function getFloorsCountAttribute(): int
    {
        return $this->shed ? $this->shed->floors : 1;
    }

    /**
     * حساب عدد الكتاكيت المباعة (ديناميكي من السجلات + أي تعديل يدوي)
     */
    public function getSoldChicksAttribute($value): int
    {
        $recordsSum = (int)$this->financialRecords()->where('type', 'revenue')->sum('quantity');
        // $value here is what's in the DB column, which we use for adjustments (like closeCycle)
        return $recordsSum + (int)($value ?? 0);
    }

    /**
     * حساب إجمالي الوزن (ديناميكي من السجلات + أي تعديل يدوي)
     */
    public function getTotalWeightAttribute($value): float
    {
        $recordsSum = (float)$this->financialRecords()->where('type', 'revenue')->sum('weight');
        return $recordsSum + (float)($value ?? 0);
    }

    /**
     * حساب عدد النافق الكلي من جدول mortalityRecords
     * (نستخدمه بدلاً من العمود القديم mortality_count)
     */
    public function getMortalityCountAttribute($value): int
    {
        $fromRecords = (int)$this->mortalityRecords()->sum('count');
        // إذا كان هناك قيمة قديمة محفوظة في الحقل نفسه (legacy) نضيفها
        return $fromRecords > 0 ? $fromRecords : (int)($value ?? 0);
    }

    /**
     * الحصول على النافق مجمّعاً حسب الدور
     * returns: [floor_number => total_count]
     */
    public function getMortalityByFloorAttribute(): array
    {
        return $this->mortalityRecords()
            ->selectRaw('floor_number, SUM(count) as total')
            ->groupBy('floor_number')
            ->orderBy('floor_number')
            ->get()
            ->pluck('total', 'floor_number')
            ->toArray();
    }

    /**
     * الحصول على مصروفات الأصناف (علف/دواء) مجمّعة حسب الدور
     * returns: [floor_number => total_cost]
     */
    public function getExpensesByFloorAttribute(): array
    {
        return $this->dispensations()
            ->selectRaw('floor_number, SUM(total_cost) as total')
            ->groupBy('floor_number')
            ->orderBy('floor_number')
            ->get()
            ->pluck('total', 'floor_number')
            ->toArray();
    }

    /**
     * إجمالي كمية العلف المستهلكة
     */
    public function getTotalFeedConsumedAttribute(): float
    {
        return $this->dispensations()
            ->whereHas('item', function($q) {
                $q->where('category', 'feed');
            })
            ->sum('quantity');
    }

    /**
     * استهلاك العلف مجمّعاً حسب الدور
     * returns: [floor_number => total_quantity]
     */
    public function getFeedConsumedByFloorAttribute(): array
    {
        return $this->dispensations()
            ->whereHas('item', function($q) {
                $q->where('category', 'feed');
            })
            ->selectRaw('floor_number, SUM(quantity) as total')
            ->groupBy('floor_number')
            ->orderBy('floor_number')
            ->get()
            ->pluck('total', 'floor_number')
            ->toArray();
    }

    /**
     * حساب عدد الكتاكيت المتبقية
     */
    public function getExpectedRemainingAttribute(): int
    {
        return $this->initial_chicks - $this->mortality_count - $this->sold_chicks;
    }

    /**
     * حساب المصروفات الإجمالية
     */
    public function getTotalExpensesAttribute(): float
    {
        return $this->financialRecords()
            ->where('type', 'expense')
            ->sum('amount');
    }

    /**
     * حساب الإيرادات الإجمالية
     */
    public function getTotalRevenuesAttribute(): float
    {
        return $this->financialRecords()
            ->where('type', 'revenue')
            ->sum('amount');
    }

    /**
     * حساب صافي الحاصل
     */
    public function getNetProfitAttribute(): float
    {
        return $this->total_revenues - $this->total_expenses;
    }

    /**
     * زيادة عدد النافق (legacy - للتوافق مع الكود القديم)
     */
    public function incrementMortality(int $count = 1): void
    {
        $this->increment('mortality_count', $count);
    }

    /**
     * إغلاق الدورة والتحقق من الفروقات
     */
    public function closeCycle(int $soldChicks, float $soldWeight): array
    {
        $expectedRemaining = $this->expected_remaining;
        $discrepancy = $expectedRemaining - $soldChicks;

        $this->update([
            'sold_chicks'  => (int)($this->sold_chicks ?? 0) + $soldChicks,
            'total_weight' => (float)($this->total_weight ?? 0) + $soldWeight,
            'status'       => 'completed',
            'end_date'     => now()->toDateString(),
        ]);

        return [
            'success'           => true,
            'expected_remaining' => $expectedRemaining,
            'sold_chicks'       => $soldChicks,
            'discrepancy'       => $discrepancy,
            'has_loss'          => $discrepancy > 0,
            'message'           => $discrepancy == 0
                ? 'تمت عملية الإغلاق بنجاح بدون فروقات'
                : ($discrepancy > 0
                    ? "تم اكتشاف خسائر غير مسجلة: {$discrepancy} كتكوتة"
                    : "تم بيع كمية أكثر من المتوقع: " . abs($discrepancy) . " كتكوتة"),
        ];
    }

    /**
     * حساب متوسط الوزن العام للدورة
     */
    public function getAverageWeightAttribute()
    {
        if ($this->sold_chicks && $this->sold_chicks > 0) {
            return $this->total_weight / $this->sold_chicks;
        }
        return 0;
    }
}
