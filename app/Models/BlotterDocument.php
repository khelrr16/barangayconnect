<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlotterDocument extends Model
{
    protected $fillable = [
        'blotter_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by',
    ];

    public function blotter(): BelongsTo
    {
        return $this->belongsTo(BlotterRecord::class, 'blotter_id');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) ($this->file_size ?? 0);
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) floor(log($bytes, 1024));
        $power = max(0, min($power, count($units) - 1));
        $value = $bytes / (1024 ** $power);

        return rtrim(rtrim(number_format($value, 2), '0'), '.') . ' ' . $units[$power];
    }

    public function getFileIconAttribute(): string
    {
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);

        return match (strtolower($extension)) {
            'pdf' => 'fa-file-pdf text-danger',
            'doc', 'docx' => 'fa-file-word text-primary',
            'xls', 'xlsx', 'csv' => 'fa-file-excel text-success',
            'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image text-info',
            'txt' => 'fa-file-lines text-secondary',
            default => 'fa-file text-muted'
        };
    }

    public function getIsImageAttribute(): bool
    {
        $extension = strtolower((string) pathinfo($this->file_name, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'], true);
    }
}
