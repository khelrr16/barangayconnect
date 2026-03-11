<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Committee extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description'];

    public function officials()
    {
        return $this->hasMany(Official::class);
    }
}
