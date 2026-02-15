<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'sex',
        'birthday',
        'birthplace',
        'citizenship',
        'civil_status',
        'contact_number',
        'email',
        'ownership',
        'registered_voter',
        'precinct_number',
        'residence_since',
        'household_id',
        'role',
        'educational_attainment',
        'occupation',
        'total_income',
        'benificiary_4ps',
    ];

    public function household()
    {
        return $this->belongsTo(\App\Models\Household::class);
    }

    public function healthProfile()
    {
        return $this->hasOne(HealthProfile::class);
    }
}
