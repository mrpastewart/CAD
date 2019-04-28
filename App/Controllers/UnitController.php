<?php
namespace App\Controllers;

use App\Models\IncidentLog;
use App\Models\Incident;
use Psr\Container\ContainerInterface;
use App\Models\Unit;
use App\Models\Shift;

Class UnitController
{
    protected $container;

    /**
     * constructor receives container instance
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function update($request, $response, $args)
    {
        $unit = Unit::find($args['id']);

        if (!$unit) {
            return $response->withStatus(404);
        }

        if (
            $unit->shift->status !== Shift::STATUS_ACTIVE
        ) {
            return $response->withStatus(403);
        }

        $params = $request->getParsedBody();

        $oldUnit = clone $unit;
        try {
            $unit->fill($params);

            $unit->save();
        } catch (\Illuminate\Database\Eloquent\MassAssignmentException $e) {
            return $response->withJson(['error' => 'Invalid attributes sent'], 400);
        }

        // if unit has incident and set state 2, 7, 11,
        if (in_array($unit->status, [2,7,11]) && $unit->incident_id) {
            $this->container->get('db')
                            ->table('incident_unit')
                            ->where('unit_id', $unit->id)
                            ->where('status', Incident::UNIT_STATUS_ASSIGNED)
                            ->update(['status' => Incident::UNIT_STATUS_UNASSIGNED, 'unassigned_at' => date('Y-m-d H:i:s')]);
            IncidentLog::unassignUnitFromIncident($unit->incident, $unit);
            if (in_array($unit->status, [2,7,11])) {
                $unit->incident_id = null;
            }
            $unit->save();
        } elseif ($unit->status === 6 && $oldUnit->status === 5) {
            IncidentLog::markUnitOnScene($unit);
        }
        return $response;
    }
}
