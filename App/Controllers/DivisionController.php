<?php
namespace App\Controllers;

use App\Models\Division;
use Psr\Container\ContainerInterface;

Class DivisionController
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
        $divisions = Division::all();
        return $response->withJson($divisions);
    }
}
