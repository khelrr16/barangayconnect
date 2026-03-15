<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id',
        'batch_number',
        'supplier',
        'expiry_date',
        'received_date',
        'quantity_received',
        'quantity_remaining',
        'quantity_used',
        'quantity_wasted',
        'quantity_expired',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'received_date' => 'date',
    ];
}
