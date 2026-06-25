<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortalityRecord extends Model
{
    protected $fillable = [
        'cycle_id',
        'floor_number',
        'count',
        'record_date',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }
}
