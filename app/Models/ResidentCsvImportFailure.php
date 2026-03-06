<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentCsvImportFailure extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_csv_import_id',
        'row_number',
        'payload',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function import()
    {
        return $this->belongsTo(ResidentCsvImport::class, 'resident_csv_import_id');
    }
}
