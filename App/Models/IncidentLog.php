<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentLog extends Model
{
    protected $fillable = [
        'incident_id',
        'unit_id',
        'user_id',
        'details',
        'type',
        'status'
    ];

    const TYPE_SYSTEM = 1;
    const TYPE_UNIT = 2;
    const TYPE_FCR = 3;

    public function incident()
    {
        return $this->belongsTo('\App\Models\Incident');
    }

    public static function assignUnitToIncident(\App\Models\Incident $incident, \App\Models\Unit $unit): IncidentLog
    {
        return IncidentLog::create([
            'unit_id' => $unit->id,
            'incident_id' => $incident->id,
            'type' => self::TYPE_SYSTEM,
            'details' => "Callsign {$unit->name} ({$unit->occupant_string}) has been attached."
        ]);
    }

    public static function unassignUnitFromIncident(\App\Models\Incident $incident, \App\Models\Unit $unit): IncidentLog
    {
        return IncidentLog::create([
            'unit_id' => $unit->id,
            'incident_id' => $incident->id,
            'type' => self::TYPE_SYSTEM,
            'details' => "Callsign {$unit->name} ({$unit->occupant_string}) has cleared."
        ]);
    }

    public static function markUnitOnScene(\App\Models\Unit $unit): IncidentLog
    {
        return IncidentLog::create([
            'unit_id' => $unit->id,
            'incident_id' => $unit->incident->id,
            'type' => self::TYPE_SYSTEM,
            'details' => "Callsign {$unit->name} ({$unit->occupant_string}) is on scene."
        ]);
    }
}
