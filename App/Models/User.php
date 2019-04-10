<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'session_id'
    ];

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
