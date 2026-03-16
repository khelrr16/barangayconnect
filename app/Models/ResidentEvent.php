<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentEvent extends Model
{
    protected $fillable = [
        'resident_id',
        'event_type', //Deceased, Transferred, Separated
        'description',
        'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }
}
