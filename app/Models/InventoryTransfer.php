<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransfer extends Model
{
    protected $fillable = ['item_id', 'shed_id', 'quantity', 'unit_cost', 'total_cost', 'transfer_date', 'notes'];
    protected $casts = ['transfer_date' => 'date'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }
}
