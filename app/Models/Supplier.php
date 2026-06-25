<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    protected $fillable = ['name','phone','email','address','opening_balance','notes'];

    public function items(): HasMany { return $this->hasMany(Item::class); }
    public function purchaseInvoices(): HasMany { return $this->hasMany(PurchaseInvoice::class); }

    public function journalLines() {
        return JournalEntryLine::where('account_type','supplier')->where('account_id',$this->id);
    }

    // رصيد المورد = رصيد افتتاحي + مجموع الدائن (مشتريات) - مجموع المدين (مدفوعات)
    public function getBalanceAttribute(): float {
        $credit = $this->journalLines()->sum('credit');
        $debit  = $this->journalLines()->sum('debit');
        return $this->opening_balance + $credit - $debit;
    }

    public function hasChartAccount(): bool {
        return \App\Models\ChartOfAccount::whereIn('linkable_type', [self::class, class_basename(self::class)])
            ->where('linkable_id', $this->id)
            ->exists();
    }

    public function chartAccount()
    {
        return $this->hasOne(\App\Models\ChartOfAccount::class, 'linkable_id', 'id')
            ->whereIn('linkable_type', [self::class, class_basename(self::class)]);
    }
}
