<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $appends = [
        'household_no',
        'blk_lot_unit',
        'household_head',
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

    public function head()
    {
        return $this->hasOne(Resident::class, 'household_id')->where('role', 'head');
    }

    public function residents()
    {
        return $this->hasMany(Resident::class, 'household_id');
    }
}
