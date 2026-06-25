<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'description'];

    public function entries()
    {
        return $this->hasMany(MedicineEntry::class);
    }

    public function dispensations()
    {
        return $this->hasMany(MedicineDispensation::class);
    }

    // حساب الرصيد الحالي الإجمالي
    public function getCurrentStockAttribute()
    {
        return $this->entries()->sum('remaining_quantity');
    }
}
