<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CycleDispensation extends Model
{
    protected $fillable = [
        'cycle_id', 'shed_id', 'item_id', 'floor_number', 
        'quantity', 'unit_cost', 'total_cost', 'dispensation_date', 'notes'
    ];
    protected $casts = ['dispensation_date' => 'date'];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }
}
