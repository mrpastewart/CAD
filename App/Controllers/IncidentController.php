<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\Unit;
use App\Models\User;

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

        if ($incident->status !== Incident::STATUS_ACTIVE && $incident->status !== Incident::STATUS_CREATED) {
            return $response->withJson(['error' => 'Incident is not active'], 400);
        }

        if ($incident->status === Incident::STATUS_CREATED) {
            $incident->status = Incident::STATUS_ACTIVE;
            $incident->save();
        }

        $unit = Unit::find($args['unit_id']);
        if (!$unit) {
            return $response->withJson(['error' => 'Unit not found'], 404);
        }

        $incident->assignUnit($unit);
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

        $incident->unassignUnit($unit);
        IncidentLog::unassignUnitFromIncident($incident, $unit);
    }

    public function index($request, $response, $args)
    {
        // Even though this is the incident index
        // it's serving as the default refreshable information hub

        $incidents = Incident::where('shift_id', $args['shift_id'])
                               ->with(['units', 'logs'])
                               ->get();

        $units = Unit::where('shift_id', $args['shift_id'])->get();

        return $response->withJson ([
            'incidents' => $incidents,
            'units' => $units
        ]);
    }

    public function addNote($request, $response, $args)
    {
        $incident = Incident::find($args['id']);
        if (!$incident) {
            return $response->withStatus(404);
        }

        if ($incident->status !== Incident::STATUS_ACTIVE) {
            return $response->withStatus(403);
        }

        $user = User::where('session_id', $_SESSION['user_ref'])->first();
        if ($user === null || $user->session_id !== $_SESSION['user_ref']) {
            return $response->withStatus(401);
        }

        $params = $request->getParsedBody();

        if (!isset($params['content'])) {
            return $response->withStatus(400);
        }

        $incidentLog = IncidentLog::create([
            'incident_id' => $incident->id,
            'unit_id' => $user->unit_id,
            'user_id' => $user->id,
            'type' => IncidentLog::TYPE_UNIT,
            'details'  => strip_tags($params['content'])
        ]);

        if (isset($params['close']) && $params['close'] == true) {
            $incident->close();
        }
        return $response;
    }

    public function reopen($request, $response, $args)
    {
        $incident = Incident::find($args['id']);
        if (!$incident) {
            return $response->withStatus(404);
        }

        if (
            $incident->status !== Incident::STATUS_COMPLETED
            && $incident->status !== Incident::STATUS_REJECTED
        ) {
            return $response->withStatus(403);
        }
        $incident->status = Incident::STATUS_CREATED;
        $incident->save();
        return $response;
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

        $params = $request->getParsedBody();
        try {
            $incident->fill($params);

            $incident->save();
        } catch (\Illuminate\Database\Eloquent\MassAssignmentException $e) {
            return $response->withJson(['error' => 'Invalid attributes sent'], 400);
        }

        return $response;
    }
}
