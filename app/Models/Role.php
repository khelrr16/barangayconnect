<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'committee_id',
    ];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function isCommitteeRole(): bool
    {
        return $this->committee_id !== null || $this->name === 'committee_head';
    }
}
