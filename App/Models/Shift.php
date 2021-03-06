<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 2;

    public function units()
    {
        return $this->hasMany('\App\Models\Unit');
    }

    public function incidents()
    {
        return $this->hasMany('\App\Models\Incident');
    }
}
