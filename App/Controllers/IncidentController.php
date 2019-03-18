<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use App\Models\Incident;

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
        $incident = Incident::where('id', $args['id'])->with(['units', 'logs'])->get();
        return $response->withJson($incident);
    }
}
