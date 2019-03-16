<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$config = [];
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'user';
$config['db']['pass']   = 'password';
$config['db']['dbname'] = 'exampleapp';

$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['view'] = new \Slim\Views\PhpRenderer('../views/');


$app->get('/mdt', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'mdt.php', []);
    return $response;
});
$app->get('/dispatcher', function (Request $request, Response $response, array $args) {
    $response = $this->view->render($response, 'dispatcher.php', []);
    return $response;
});

$app->run();
