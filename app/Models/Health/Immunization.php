<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Immunization extends Model
{
    protected $fillable = [
        'infant_id',
        'medicine_id',
        'dose_number',
        'administration_date',
    ];

    protected $casts = [
        'administration_date' => 'date',
    ];

    public function infant()
    {
        return $this->belongsTo(Infant::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
