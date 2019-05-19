<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

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

    public function units() // where pivot?
    {
        return $this->belongsToMany('\App\Models\Unit');
    }

    /**
     * Closes incident and removes all units currently attached
     */
    public function close(): void
    {
        $this->status = Incident::STATUS_COMPLETED;
        $this->unassignAllUnits();
        $this->save();
    }

    /**
     * Adds a unit to this Incident
     * @param  Unit   $unit
     */
    public function assignUnit(Unit $unit): void
    {
        $this->units()
             ->where('unit_id', $unit->id)
             ->where('incident_unit.status', Incident::UNIT_STATUS_ASSIGNED)
             ->update(['incident_unit.status' => Incident::UNIT_STATUS_UNASSIGNED]);

        $this->units()->attach($unit->id, ['status' => Incident::UNIT_STATUS_ASSIGNED, 'assigned_at' => date('Y-m-d H:i:s')]);
        $unit->status = Unit::STATUS_ON_ROUTE;
        $unit->incident_id = $this->id;
        $unit->save();
    }

    /**
     * Removes a unit from this incident
     * @param Unit $unit
     */
    public function unassignUnit(Unit $unit): void
    {
        $this->units()
             ->where('unit_id', $unit->id)
             ->where('incident_unit.status', Incident::UNIT_STATUS_ASSIGNED)
             ->update(['incident_unit.status' => Incident::UNIT_STATUS_UNASSIGNED, 'unassigned_at' => date('Y-m-d H:i:s')]);

        $unit->status = Unit::STATUS_AVAILABLE_PATROL;
        $unit->incident_id = null;
        $unit->save();
    }

    /**
     * Removes all units from this incident
     */
    public function unassignAllUnits(): void
    {
        foreach ($this->units as $unit) {
            $this->unassignUnit($unit);
        }
    }
}
