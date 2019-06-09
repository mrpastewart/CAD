<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Incident;
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
        $user = User::where('session_id', $_SESSION['user_ref'])->first();
        $unit = Unit::find($user->unit_id);

        if ($unit == null) {
            // User is not booked with a unit anymore
            return $response->withJson([], 403);
        }

        $incident = null;
        if ($unit->incident_id !== null) {
            $incident = Incident::where('id', $unit->incident_id)->with('logs')->first();
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
