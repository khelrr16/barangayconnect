<?php

namespace App\Models;

use App\Models\Health\Infant;
use Illuminate\Database\Eloquent\Model;

class ForeignHousehold extends Model
{
    protected $appends = [
        'first_address',
        'second_address',
    ];

    protected $fillable = [
        'house_number',
        'street',
        'subdivision',
        'barangay',
        'city',
        'province',
    ];

    public function getFirstAddressAttribute()
    {
        $address = $this->house_number;
        if($this->street){
        $address .= ', ' . $this->street;
        }

        return $address;
    }

    public function getSecondAddressAttribute()
    {
        $address = "Brgy. {$this->barangay}, {$this->city}, {$this->province}";

        return $address;
    }

    public function infants()
    {
        return $this->hasMany(Infant::class);
    }
}
