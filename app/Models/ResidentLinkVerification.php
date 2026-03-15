<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentLinkVerification extends Model
{
    protected $fillable = [
        'user_id',
        'document_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'resident_id',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
