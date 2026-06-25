<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleInvoiceItem extends Model
{
    protected $fillable = ['invoice_id','item_id','quantity','unit_price','total'];

    public function invoice(): BelongsTo { return $this->belongsTo(SaleInvoice::class, 'invoice_id'); }
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
}