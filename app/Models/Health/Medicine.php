<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'dose_volume',
    ];

    public function currentBatch(){
        return $this->hasOne(MedicineBatch::class, 'medicine_id')->where('status','active')->oldest();
    }

    public function overall(){
        return $this->hasOne(MedicineBatch::class, 'medicine_id')
            ->where('status','active')
            ->selectRaw('medicine_id, SUM(quantity_received) as total_received, SUM(quantity_remaining) as total_remaining')
            ->groupBy('medicine_id');
    }

    public function batches(){
        return $this->hasMany(MedicineBatch::class, 'medicine_id')->orderBy('created_at', 'desc');
    }
}
