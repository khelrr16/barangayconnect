<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentProgram extends Model
{
    use HasFactory;
    protected $fillable = [
        'resident_id',
        'program_id',
        // 'batch_year',
        // 'date_received',
        // 'amount_received',
        'status',
        'remarks',
        'encoded_by'
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function encoder()
    {
        return $this->belongsTo(User::class, 'encoded_by');
    }
}
