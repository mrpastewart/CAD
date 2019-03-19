<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Incident;

class Unit extends Model
{
    const STATUS_PANIC = 0;
    const STATUS_ON_DUTY = 1;
    const STATUS_AVAILABLE_PATROL = 2;
    const STATUS_AVAILABLE_OFFICE = 3;
    const STATUS_WELFARE_BREAK = 4;
    const STATUS_ON_ROUTE = 5;
    const STATUS_ON_SCENE = 6;
    const STATUS_AVAILABLE_COMMITTED = 7;
    const STATUS_UNAVAILABLE = 8;
    const STATUS_TRANSPORT = 9;
    const STATUS_TECHNICAL_ISSUES = 10;
    const STATUS_OFF_DUTY = 11;

    /**
     * Gets active incident
     * @return Incident
     */
    public function incident()
    {
        return $this->incidents()->where('status', Incident::STATUS_ACTIVE)->get();
    }

    /**
     * Gets complete list of incidents attended
     */
    public function incidents()
    {
        return $this->belongsToMany('\App\Models\Incident');
    }
}
