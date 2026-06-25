<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = ['name','category','unit','quantity_in_stock','last_purchase_price','supplier_id','notes'];

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function invoiceItems(): HasMany { return $this->hasMany(PurchaseInvoiceItem::class); }

    public function shedInventories(): HasMany { return $this->hasMany(ShedInventory::class); }
    public function transfers(): HasMany { return $this->hasMany(InventoryTransfer::class); }
    public function dispensations(): HasMany { return $this->hasMany(CycleDispensation::class); }
}
