<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShedInventory extends Model
{
    protected $fillable = ['shed_id', 'item_id', 'quantity', 'avg_unit_cost'];

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
