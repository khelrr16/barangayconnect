<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeAssistantAccess extends Model
{
    protected $fillable = [
        'committee_head_id',
        'assistant_user_id',
        'committee_slug',
    ];

    public function committeeHead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'committee_head_id');
    }

    public function assistant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assistant_user_id');
    }
}
