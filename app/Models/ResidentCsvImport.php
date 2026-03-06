<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentCsvImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'status',
        'total_rows',
        'processed_rows',
        'success_rows',
        'failed_rows',
        'error_message',
        'started_at',
        'finished_at',
        'created_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function failures()
    {
        return $this->hasMany(ResidentCsvImportFailure::class, 'resident_csv_import_id');
    }
}
