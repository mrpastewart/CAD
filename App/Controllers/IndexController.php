<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;

Class IndexController
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
        $response = $this->container->view->render($response, 'index.php', []);
    }
}
