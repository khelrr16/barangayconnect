<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Infant extends Model
{
    protected $appends = [
        'age_in_months',
        'age_in_weeks',
        'full_name',
    ];
    
    protected $fillable = [
        'birthday',
        'family_serial_number',
        'name',
        'sex',
        'mother_name',
        'household_number',
        'foreign_household_number',
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
        return Carbon::parse($this->birthday)->diffInMonths(Carbon::now());
    }

    public function getAgeInWeeksAttribute()
    {
        return Carbon::parse($this->birthday)->diffInWeeks(Carbon::now());
    }
}
