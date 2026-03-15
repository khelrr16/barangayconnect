<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
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
}
