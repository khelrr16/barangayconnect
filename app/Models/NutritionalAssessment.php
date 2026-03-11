<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NutritionalAssessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'infant_id',
        'age_category',
        'age_in_months',
        'weight',
        'length',
        'status',
    ];
}
