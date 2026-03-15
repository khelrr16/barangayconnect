<?php

namespace App\Models;

use App\Models\Health\Infant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $appends = [
        'household_no',
        'blk_lot_unit',
        'household_head',
        'first_address',
        'second_address',
    ];
    
    protected $fillable = [
        'block',
        'lot',
        'unit',
        'street',
        'subdivision',
        'pet_count',
    ];

    public function getHouseholdNoAttribute()
    {
        return 'HH-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }

    public function getBlkLotUnitAttribute()
    {
        $address = 'Block ' . $this->block . ' Lot ' . $this->lot;
        if($this->unit) {
            $address .= ' Unit ' . $this->unit;
        }

        return $address;
    }

    public function getHouseholdHeadAttribute()
    {
        return $this->head ? $this->head->full_name : 'N/A';
    }

    public function getFirstAddressAttribute()
    {
        $address = $this->getBlkLotUnitAttribute();
        $address .= ", {$this->street}, {$this->subdivision}";
        return $address;
    }

    public function getSecondAddressAttribute()
    {
        $address = "Brgy. San Lorenzo, San Pedro City, Laguna";

        return $address;
    }

    public function head()
    {
        return $this->hasOne(Resident::class, 'household_id')->where('role', 'head');
    }

    public function residents()
    {
        return $this->hasMany(Resident::class, 'household_id');
    }

    public function infants()
    {
        return $this->hasMany(Infant::class, 'household_id');
    }
}
