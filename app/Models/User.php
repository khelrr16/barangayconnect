<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'official_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function uploads()
    {
        return $this->hasMany(ResidentCsvImport::class, 'created_by');
    }

    public function official()
    {
        return $this->belongsTo(Official::class);
    }

    public function committee()
    {
        return $this->hasOneThrough(
            Committee::class,
            Official::class,
            'id',
            'id',
            'official_id',
            'committee_id'
        );
    }
}
