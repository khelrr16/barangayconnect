<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public const TYPES = [
        'barangay_clearance' => 'Barangay Clearance',
        'certificate_of_indigency' => 'Certificate of Indigency',
        'certificate_of_residency' => 'Certificate of Residency',
        'good_moral_character' => 'Certificate of Good Moral Character',
        'barangay_id' => 'Barangay ID',
    ];

    protected $fillable = [
        'resident_id',
        'certificate_type',
        'purpose',
        'requirements',
        'status',
        'priority',
        'remarks',
        'verified_by',
        'approved_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            // Generate unique tracking code: BRGY-YYYYMMDD-XXXX
            $date = now()->format('Ymd');
            $lastRequest = self::whereDate('created_at', today())->count();
            $sequence = str_pad($lastRequest + 1, 4, '0', STR_PAD_LEFT);

            $request->tracking_code = 'BRGY-' . $date . '-' . $sequence;
            $request->submitted_at = now();
        });
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
