<?php
namespace App\Controllers;


use Psr\Container\ContainerInterface;
use App\Models\User;
use App\Models\Shift;
use App\Models\Unit;

Class UserController
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * constructor receives container instance
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function login($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (
            !isset($params['passenger'])
            || !is_bool($params['passenger'])
            || empty($params['name'])
            || empty($params['shift'])
        ) {
            return $response->withJson(['error' => 'You must fill in all the boxes'], 400);
        }

        $user = User::firstOrNew([
            'name' => trim($params['name'])
        ]);

        // get shift
        $shift = Shift::where('name', trim($params['shift']))
                        ->where('status', Shift::STATUS_ACTIVE)
                        ->first();

        if ($shift === NULL) { // shift not found
            return $response->withJson(['error' => 'Shift not found'], 400);
        }

        // get unit
        $unit = Unit::where('name', trim($params['callsign']))
                      ->where('shift_id', $shift->id)
                      ->first();

        if ($params['passenger']) {
            if ($unit === NULL) { // unit not found
                return $response->withJson(['error' => 'Callsign not found'], 400);
            }
            $user->unit_id = $unit->id;
        } else {
            if ($unit) {
                return $response->withJson(['error' => 'Callsign has already been signed up.'], 400);
            }

            $unit = new Unit;
            $unit->name = $params['callsign'];
            $unit->status = Unit::STATUS_UNAVAILABLE;
            $unit->shift_id = $shift->id;
            $unit->save();

            $user->unit_id = $unit->id;
        }

        $_SESSION['id'] = bin2hex(random_bytes(16));
        $_SESSION['user_id'] = $user->id;
        $user->session_id = $_SESSION['id'];
        $user->save();

        // Update unit
        $unit->occupant_string = $unit->getUpdatedOccupantString();
        $unit->save();
    }
}
