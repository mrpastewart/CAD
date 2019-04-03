<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Shift;

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
                         ->get();
        return $response->withJson($shifts);
    }
}
