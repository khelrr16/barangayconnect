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
        'resident_id',
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

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function residentLinkVerifications()
    {
        return $this->hasMany(ResidentLinkVerification::class);
    }

    public function committeeAssistantAccessAsAssistant()
    {
        return $this->hasOne(CommitteeAssistantAccess::class, 'assistant_user_id');
    }

    public function committeeAssistantAccessesAsHead()
    {
        return $this->hasMany(CommitteeAssistantAccess::class, 'committee_head_id');
    }

    public function isCommitteeHeadLike(): bool
    {
        if ($this->hasRole('committee_head')) {
            return true;
        }

        return $this->roles()->where('name', 'like', 'committee_head_%')->exists();
    }

    public function assistedCommitteeSlug(): ?string
    {
        return $this->committeeAssistantAccessAsAssistant?->committee_slug;
    }
}
