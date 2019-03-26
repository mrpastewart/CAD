<?php
namespace App;

use Slim\App as SlimApp;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

Class App
{
    /**
     * Slim app instance
     * @var SlimApp
     */
    private $app;

    public function run()
    {
        $this->setupSessions();
        $this->setupSlim();
        $this->setupRoutes();
        $this->app->run();
    }

    private function setupSessions()
    {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 0);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.hash_function', 'sha256');
        ini_set('session.hash_bits_per_character', 5);

        session_name('cad_session');
        session_cache_limiter(false);
        session_start();

        // Make sure we have a canary set
        if (!isset($_SESSION['canary'])) {
            session_regenerate_id(true);
            $_SESSION['canary'] = time();
        }

        // Regenerate session ID every five minutes:
        if ($_SESSION['canary'] < time() - 300) {
            session_regenerate_id(true);
            $_SESSION['canary'] = time();
        }
    }

    private function setupSlim()
    {
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

        $this->app = new SlimApp(['settings' => $config]);
        $container = $this->app->getContainer();
        $container['view'] = new \Slim\Views\PhpRenderer('../views/');

        $container['db'] = function ($container) {
            $capsule = new \Illuminate\Database\Capsule\Manager;
            $capsule->addConnection($container['settings']['db']);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        };

        $this->app->getContainer()->get("db");
    }

    public function setupRoutes()
    {
        $self = $this; // To bypass being unable to pass $this into callback

        $this->app->get('/', '\App\Controllers\IndexController:index');
        $this->app->group('', function () use ($self) {
            $self->app->get('/api/shifts/{shift_id}/incidents', '\App\Controllers\IncidentController:index');
            $self->app->get('/api/shifts/{shift_id}/incidents/{id}', '\App\Controllers\IncidentController:view');
            $self->app->patch('/api/incidents/{id}', '\App\Controllers\IncidentController:update');
            $self->app->post('/api/incidents', '\App\Controllers\IncidentController:create');
            $self->app->delete('/api/incidents/{id}', '\App\Controllers\IncidentController:close');

            $self->app->get('/mdt', function (Request $request, Response $response, array $args) {
                $response = $this->view->render($response, 'mdt.php', []);
                return $response;
            });
            $self->app->get('/dispatcher', function (Request $request, Response $response, array $args) {
                $response = $this->view->render($response, 'dispatcher.php', []);
                return $response;
            });
        })->add([$this, 'checkAuth']);
    }

    public function checkAuth($request, $response, $next)
    {
        if (is_array($request->getHeader('Accept'))) {
            $json = in_array('application/json', $request->getHeader('Accept'));
        } else {
            $json = stristr($request->getHeader('Accept'), 'application/json') !== NULL;
        }
        if (!isset($_SESSION['user_id'])) {
            return $response->withStatus(401, 'unauthorised');
        }
        $response = $next($request, $response);
        return $response;
    }
}
