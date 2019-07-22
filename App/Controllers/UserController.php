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
        if (empty($params['username']) || empty($params['password'])) {
            return $response->withJson(['error' => 'You must fill in all the boxes'], 400);
        }

        $user = User::firstOrNew([
            'name' => trim($params['username'])
        ]);
        $sessionId = bin2hex(random_bytes(16));
        $_SESSION['user_ref'] = $sessionId;
        $_SESSION['user_id'] = $user->id;
        $user->session_id = $sessionId;
        $user->save();
    }

    public function signup($request, $response, $args)
    {
        $params = $request->getParsedBody();
        if (
            !isset($params['passenger'])
            || !is_bool($params['passenger'])
            || empty($params['shift'])
            || empty($params['callsign'])
            || empty($params['division'])
        ) {
            return $response->withJson(['error' => 'You must fill in all the boxes'], 400);
        }

        $user = User::where('session_id', $_SESSION['user_ref'] ?? null)->first();
        if (!$user) {
            return $response->withJson(false,401);
        }

        // get shift
        $shift = Shift::where('id', trim($params['shift']))
                        ->where('status', Shift::STATUS_ACTIVE)
                        ->first();

        if ($shift === NULL) { // shift not found
            return $response->withJson(['error' => 'Shift not found'], 400);
        }

        // get unit
        $unit = Unit::where('name', trim($params['callsign']))
                      ->where('shift_id', $shift->id)
                      ->where('status', '<>', Unit::STATUS_OFF_DUTY)
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
            $unit->division_id = $params['division'];
            $unit->save();

            $user->unit_id = $unit->id;
        }
        $sessionId = bin2hex(random_bytes(16));
        $_SESSION['user_ref'] = $sessionId;
        $_SESSION['user_id'] = $user->id;
        $user->session_id = $sessionId;
        $user->save();

        // Update unit
        $unit->occupant_string = $unit->getUpdatedOccupantString();
        $unit->save();
    }

    public function view($request, $response, $args)
    {
        $user = User::where('session_id', $_SESSION['user_ref'] ?? null)->first();
        if (!$user) {
            return $response->withJson(false,401);
        }
        return $response->withJson($user);
    }
}
