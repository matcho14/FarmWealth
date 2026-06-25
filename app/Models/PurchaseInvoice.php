<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'invoice_number','supplier_id','treasury_id','invoice_date',
        'total_amount','paid_amount','payment_status','notes'
    ];
    protected $casts = ['invoice_date' => 'date'];

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function treasury(): BelongsTo { return $this->belongsTo(Treasury::class); }
    public function items(): HasMany { return $this->hasMany(PurchaseInvoiceItem::class, 'invoice_id'); }

    public function getRemainigAmountAttribute(): float {
        return $this->total_amount - $this->paid_amount;
    }

    public static function generateNumber(): string {
        $year  = now()->format('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'PUR-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
