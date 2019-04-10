<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\Unit;
use App\Models\User;

Class MdtController
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

    public function index($request, $response, $args)
    {
        $user = User::find($_SESSION['user_id']);
        $unit = Unit::find($user->unit_id);

        $incident = null;
        if ($unit->incident_id !== null) {
            $incident = Incident::find($unit->incident_id);
        }

        $units = Unit::select(['name', 'status', 'occupant_string'])->get();

        return $response->withJson([
            'user' => $user,
            'unit' => $unit,
            'incident' => $incident,
            'units' => $units
        ]);
    }
}
