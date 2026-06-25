<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialRecord extends Model
{
    protected $fillable = [
        'cycle_id',
        'shed_id',
        'type',
        'quantity',
        'weight',
        'amount',
        'paid_amount',
        'description',
        'record_date',
        'floor_number',
        'item_id',
        'dispensation_id',
        'treasury_id',
        'client_id',
        'chart_of_account_id',
        'payment_type'  // cash or credit
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function shed(): BelongsTo
    {
        return $this->belongsTo(Shed::class);
    }

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function treasury(): BelongsTo
    {
        return $this->belongsTo(Treasury::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getAverageWeightAttribute()
    {
        if ($this->quantity && $this->quantity > 0) {
            return $this->weight / $this->quantity;
        }
        return 0;
    }
}
