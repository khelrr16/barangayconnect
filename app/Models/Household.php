<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'block',
        'lot',
        'unit',
        'street',
        'barangay',
        'city',
        'province',
        'pet_count',
    ];

    public function residents()
    {
        return $this->hasMany(\App\Models\Resident::class);
    }
}
