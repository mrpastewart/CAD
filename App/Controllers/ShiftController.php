<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Shift;
use App\Models\Unit;

Class ShiftController
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
        $shifts = Shift::select(['id', 'name'])
                         ->where('status', Shift::STATUS_ACTIVE)
                         ->with(['units', 'incidents'])
                         ->get();
        foreach ($shifts as $key => $shift) {
            $shifts[$key]['incident_count'] = count($shift['incidents']);
            $shifts[$key]['unit_count'] = count($shift['units']);
            $shifts[$key]['dispatcher_count'] = 0;
            unset($shifts[$key]['units']);
            unset($shifts[$key]['incidents']);
        }
        return $response->withJson($shifts);
    }

    public function unitIndex($request, $response, $args)
    {
        $units = Unit::where('shift_id', $args['shift_id'])
                     ->where('status', '<>', 11)
                     ->get();
        return $response->withJson($units);
    }
}
