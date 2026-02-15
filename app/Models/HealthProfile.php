<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'health_condition',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
