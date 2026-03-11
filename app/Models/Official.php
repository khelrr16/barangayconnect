<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Official extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 
        'position', 
        'committee_id', 
        'term_start', 
        'term_end'
    ];
    
    protected $casts = [
        'term_start' => 'date', // or 'datetime'
        'term_end' => 'date',   // if you have this field
    ];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
