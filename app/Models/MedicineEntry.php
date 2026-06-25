<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineEntry extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_id', 'quantity', 'remaining_quantity', 'price', 'entry_date'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
