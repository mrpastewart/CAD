<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'title',
        'location1',
        'location2',
        'type',
        'details',
        'grading',
        'status',
        'interop',
        'owner_id',
        'shift_id'
    ];

    const STATUS_DRAFT = 1;
    const STATUS_CREATED = 2;
    const STATUS_ACTIVE = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_REJECTED = 5;

    const UNIT_STATUS_ASSIGNED = 1;
    const UNIT_STATUS_UNASSIGNED = 2;

    public function logs()
    {
        return $this->hasMany('\App\Models\IncidentLog');
    }

    public function units()
    {
        return $this->belongsToMany('\App\Models\Unit');
    }
}
