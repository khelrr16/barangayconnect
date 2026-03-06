<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name',
        'description',
        'agency',
        'is_active',
    ];

    public function residents()
    {
        return $this->belongsToMany(Resident::class, 'resident_programs')
                    ->withPivot('batch_year', 'date_received', 'amount_received', 'status', 'remarks', 'encoded_by')
                    ->withTimestamps();
    }
}
