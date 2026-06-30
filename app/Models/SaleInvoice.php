<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoice extends Model
{
    protected $fillable = [
        'invoice_number','client_id','treasury_id','invoice_date',
        'total_amount','paid_amount','payment_status','notes',
        'cycle_id', 'shed_id'
    ];
    protected $casts = ['invoice_date' => 'date'];

    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function treasury(): BelongsTo { return $this->belongsTo(Treasury::class); }
    public function items(): HasMany { return $this->hasMany(SaleInvoiceItem::class, 'invoice_id'); }
    public function cycle(): BelongsTo { return $this->belongsTo(Cycle::class); }
    public function shed(): BelongsTo { return $this->belongsTo(Shed::class); }

    public function getRemainigAmountAttribute(): float {
        return $this->total_amount - $this->paid_amount;
    }

    public static function generateNumber(): string {
        $year  = now()->format('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'SAL-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}