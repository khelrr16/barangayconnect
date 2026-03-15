<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'rbi_no',
        'full_name',
        'raw_rbi_no',
        'age',
        'address',
        'length_of_stay',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'sex',
        'birthday',
        'birthplace',
        'religion',
        'citizenship',
        'civil_status',
        'contact_number',
        'email',
        'ownership',
        'registered_voter',
        'precinct_number',
        'household_id',
        'residence_since',
        'role',
        'educational_attainment',
        'occupation',
        'employment_status',
        'monthly_income',
        'status'
    ];

    public function getRbiNoAttribute(): string
    {
        return 'R-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getRawRbiNoAttribute(): string
    {
        return 'R-' . $this->id;
    }

    public function getFullNameAttribute(): string
    {
        $parts = [
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->extension_name
        ];
        
        // Filter out empty values and implode with spaces
        return implode(' ', array_filter($parts));
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birthday)->age;
    }

    public function getAddressAttribute(): string
    {
        if ($this->household) {
            $address = $this->household->first_address;
            $address .= ", " . $this->household->second_address;
            return $address;
        }
        return 'N/A';
    }

    public function getLengthOfStayAttribute(): string
    {
        return Carbon::now()->year - $this->residence_since. ' years';
    }

    public function household()
    {
        return $this->belongsTo(Household::class, 'household_id');
    }

    public function household_head()
    {
        return $this->household->head;
    }

    public function commOrgs()
    {
        return $this->hasMany(CommunityOrganization::class, 'resident_id');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'resident_programs')
                    ->withPivot('status', 'remarks', 'encoded_by')
                    ->withTimestamps();
    }

    public function healthProfile()
    {
        return $this->hasMany(HealthProfile::class, 'resident_id');
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }
}
