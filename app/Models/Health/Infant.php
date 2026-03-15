<?php

namespace App\Models\Health;

use App\Models\ForeignHousehold;
use App\Models\Household;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Infant extends Model
{
    protected $appends = [
        'age_in_months',
        'age_in_weeks',
        'age',
    ];
    
    protected $fillable = [
        'birthday',
        'family_serial_number',
        'name',
        'sex',
        'mother_name',
        'household_id',
        'foreign_household_id',
        'cpab',

        'breastfeed_after_birth',
        'iron_1',
        'iron_2',
        'iron_3',
        'breastfeed_exclusively',
        'breastfeed_exclusively_date',

        'complementary_feeding',
        'complementary_feeding_2',
        'vitamin_a',
        'mnp_start',
        'mnp_end',
        'malnutrition_type',
        'fic',
        'cic',
        'status',
        'remarks',
    ];

    protected $casts = [
        'birthday' => 'date',
        'breastfeed_after_birth' => 'date',
        'iron_1' => 'date',
        'iron_2' => 'date',
        'iron_3' => 'date',
        'breastfeed_exclusively_date' => 'date',
        'vitamin_a' => 'date',
        'mnp_start' => 'date',
        'mnp_end' => 'date',
        'fic' => 'date',
        'cic' => 'date',
    ];

    public function getAgeInMonthsAttribute()
    {
        return round(Carbon::parse($this->birthday)->diffInMonths(Carbon::now())) . ' month/s';
    }

    public function getAgeInWeeksAttribute()
    {
        return floor(Carbon::parse($this->birthday)->diffInWeeks(Carbon::now())) . ' week/s';
    }
    public function getAgeAttribute()
    {
        $age = floor(Carbon::parse($this->birthday)->diffInMonths(Carbon::now())) . ' month/s';

        if($age < 1){
            $age = floor(Carbon::parse($this->birthday)->diffInWeeks(Carbon::now())) . ' week/s';
        } 
        
        if($age < 1){
            $age = floor(Carbon::parse($this->birthday)->diffInDays(Carbon::now())) . ' day/s';
        }
        return $age;
    }


    public function household()
    {
        return $this->belongsTo(Household::class, 'household_id');
    }

    public function foreign_household()
    {
        return $this->belongsTo(ForeignHousehold::class, 'foreign_household_id');
    }

}