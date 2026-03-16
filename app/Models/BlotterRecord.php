<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlotterRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blotter_number',
        'complainant_name',
        'complainant_contact',
        'complainant_address',
        'respondent_name',
        'respondent_contact',
        'respondent_address',
        'case_type',
        'date_filled',
        'case_description',
        'witnesses',
        'assigned_official_id',
        'hearing_datetime',
        'hearing_venue',
        'status',
        'created_by',
        'updated_by',
        'filed_at',
    ];

    protected $casts = [
        'date_filled' => 'date',
        'hearing_datetime' => 'datetime',
        'filed_at' => 'datetime',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(BlotterDocument::class, 'blotter_id');
    }

    public function assignedOfficial(): BelongsTo
    {
        return $this->belongsTo(Official::class, 'assigned_official_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
