<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NutritionalAssessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'infant_id',
        'category',
        'age',
        'weight',
        'length',
        'status',
        'assessment_date',
    ];

    protected $casts = [
        'assessment_date' => 'date',
    ];
}
