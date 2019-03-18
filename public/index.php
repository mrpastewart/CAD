<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$config = [
    // Slim Settings
    'determineRouteBeforeAppMiddleware' => false,
    'displayErrorDetails' => true,
    'db' => [
        'driver' => 'mysql',
        'host' => 'mariadb',
        'database' => 'cad',
        'username' => 'root',
        'password' => 'root',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => 'cad_',
    ]
];

$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer('../views/');

$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$app->getContainer()->get("db");

$app->get('/mdt', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'mdt.php', []);
    return $response;
});
$app->get('/dispatcher', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'dispatcher.php', []);
    return $response;
});

$app->get('/api/incidents/{id}', '\App\Controllers\IncidentController:view');

$app->run();
