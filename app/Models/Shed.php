<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shed extends Model
{
    protected $fillable = ['name', 'description', 'status', 'floors'];

    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(ShedInventory::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(InventoryTransfer::class);
    }

    public function dispensations(): HasMany
    {
        return $this->hasMany(CycleDispensation::class);
    }

    public function activeCycles()
    {
        return $this->cycles()->where('status', 'active')->get();
    }

    public function completedCycles()
    {
        return $this->cycles()->where('status', 'completed')->get();
    }
}
