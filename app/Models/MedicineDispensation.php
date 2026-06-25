<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineDispensation extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_id', 'cycle_id', 'quantity', 'total_cost', 'dispensation_date'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }
}
