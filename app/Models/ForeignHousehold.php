<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignHousehold extends Model
{
    protected $fillable = [
        'house_number',
        'street',
        'subdivision',
        'barangay',
        'city',
        'province',
    ];

    public function infants()
    {
        return $this->hasMany(Infant::class);
    }
}
