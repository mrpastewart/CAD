<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\Unit;

Class IncidentController
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

    public function view($request, $response, $args)
    {
        $incident = Incident::where('shift_id', $args['shift_id'])
                              ->where('id', $args['id'])
                              ->with(['units', 'logs'])->get();
        return $response->withJson($incident);
    }

    public function create($request, $response, $args)
    {
        $incident = new Incident;
        // TODO: Only add if shift is not finished
        $params = $request->getParsedBody();
        $incident->shift_id = $params['shift_id'];
        $incident->owner_id = 1;
        $incident->status = Incident::STATUS_ACTIVE;
        $incident->save();

        return $response->withJson($incident);
    }

    public function close($request, $response, $args)
    {
        $incident = Incident::find($args['id']);
        if (!$incident) {
            return $response->withStatus(404);
        }

        if (
            $incident->status === Incident::STATUS_COMPLETED
            || $incident->status === Incident::STATUS_REJECTED
        ) {
            return $response->withStatus(403);
        }

        $incident->status = Incident::STATUS_COMPLETED;
        $incident->save();

        return $response;
    }

    public function assignUnit($request, $response, $args)
    {
        $incident = Incident::find($args['id']);
        if (!$incident) {
            return $response->withJson(['error' => 'Incident not found'], 404);
        }

        if ($incident->status !== Incident::STATUS_ACTIVE ) {
            return $response->withJson(['error' => 'Incident is not active'], 400);
        }

        $unit = Unit::find($args['unit_id']);
        if (!$unit) {
            return $response->withJson(['error' => 'Unit not found'], 404);
        }

        $this->container->get('db')
                        ->table('incident_unit')
                        ->where('unit_id', $unit->id)
                        ->where('status', Incident::UNIT_STATUS_ASSIGNED)
                        ->update(['status' => Incident::UNIT_STATUS_UNASSIGNED]);

        $incident->units()->attach($unit->id, ['status' => Incident::UNIT_STATUS_ASSIGNED, 'assigned_at' => date('Y-m-d H:i:s')]);
        $unit->status = Unit::STATUS_ON_ROUTE;
        $unit->incident_id = $incident->id;
        $unit->save();

        IncidentLog::assignUnitToIncident($incident, $unit);
    }

    public function unassignUnit($request, $response, $args)
    {
        $incident = Incident::find($args['id']);
        if (!$incident) {
            return $response->withJson(['error' => 'Incident not found'], 404);
        }

        if ($incident->status !== Incident::STATUS_ACTIVE ) {
            return $response->withJson(['error' => 'Incident is not active'], 400);
        }

        $unit = Unit::find($args['unit_id']);
        if (!$unit) {
            return $response->withJson(['error' => 'Unit not found'], 404);
        }

        $this->container->get('db')
                        ->table('incident_unit')
                        ->where('unit_id', $unit->id)
                        ->where('status', Incident::UNIT_STATUS_ASSIGNED)
                        ->update(['status' => Incident::UNIT_STATUS_UNASSIGNED, 'unassigned_at' => date('Y-m-d H:i:s')]);

        $unit->status = Unit::STATUS_AVAILABLE_PATROL;
        $unit->incident_id = null;
        $unit->save();

        IncidentLog::unassignUnitToIncident($incident, $unit);
    }

    public function index($request, $response, $args)
    {
        // Even though this is the incident index
        // it's serving as the default refreshable information hub

        $incidents = Incident::where('shift_id', $args['shift_id'])
                               ->where(function ($query) {
                                   $query->where('status', '=', Incident::STATUS_DRAFT)
                                          ->orWhere('status', '=', Incident::STATUS_ACTIVE);
                               })
                               ->with(['units', 'logs'])
                               ->get();

        $units = Unit::where('shift_id', $args['shift_id'])->get();

        return $response->withJson ([
            'incidents' => $incidents,
            'units' => $units
        ]);
    }

    public function update($request, $response, $args)
    {
        $incident = Incident::find($args['id']);
        if (!$incident) {
            return $response->withStatus(404);
        }

        if (
            $incident->status !== Incident::STATUS_ACTIVE
            && $incident->status !== Incident::STATUS_CREATED
        ) {
            return $response->withStatus(403);
        }
        // TODO: Only edit if shift not over

        $params = $request->getParsedBody();
        $incident->title = $params['title'];
        $incident->interop = $params['interop'];
        $incident->type = $params['type'];
        $incident->grading = $params['grading'];
        $incident->details = $params['details'];
        $incident->save();

        return $response;
    }
}
