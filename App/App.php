<?php
namespace App;

use Slim\App as SlimApp;
use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

Class App
{
    /**
     * Slim app instance
     * @var SlimApp
     */
    private $app;

    /**
     * Sets up then runs the App
     */
    public function run() : void
    {
        $this->setupSessions();
        $this->setupSlim();
        $this->setupRoutes();
        $this->app->run();
    }

    /**
     * Sets up PHP session settings
     */
    private function setupSessions() : void
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

        if (!isset($_SESSION['canary'])) {
            session_regenerate_id(true);
            $_SESSION['canary'] = time();
        }

        if ($_SESSION['canary'] < time() - 300) {
            session_regenerate_id(true);
            $_SESSION['canary'] = time();
        }
    }

    /**
     * Starts slim with basic config vars
     */
    private function setupSlim() : void
    {
        $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../');
        $dotenv->load();
        $config = [
            // Slim Settings
            'determineRouteBeforeAppMiddleware' => false,
            'displayErrorDetails' => true,
            'db' => [
                'driver' => 'mysql',
                'host' => getenv('DB_HOST'),
                'database' => getenv('DB_DATABASE'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
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

    /**
     * Sets up the Slim routing
     */
    public function setupRoutes() : void
    {
        $self = $this; // To bypass being unable to pass $this into callback

        $this->app->get('/', '\App\Controllers\IndexController:index');
        $self->app->post('/auth/login', '\App\Controllers\UserController:login');
        $self->app->get('/api/shifts', '\App\Controllers\ShiftController:index');

        $this->app->group('', function () use ($self) {

            // Shifts
            $self->app->get('/api/mdt', '\App\Controllers\MdtController:index');

            $self->app->get('/api/shifts/{shift_id}/incidents', '\App\Controllers\IncidentController:index');
            $self->app->get('/api/shifts/{shift_id}/incidents/{id}', '\App\Controllers\IncidentController:view');

            // Incidents
            $self->app->patch('/api/incidents/{id}', '\App\Controllers\IncidentController:update');
            $self->app->post('/api/incidents/{id}/units/{unit_id}', '\App\Controllers\IncidentController:assignUnit');
            $self->app->delete('/api/incidents/{id}/units/{unit_id}', '\App\Controllers\IncidentController:unassignUnit');
            $self->app->post('/api/incidents', '\App\Controllers\IncidentController:create');
            $self->app->delete('/api/incidents/{id}', '\App\Controllers\IncidentController:close');

            // Units
            $self->app->patch('/api/units/{id}', '\App\Controllers\UnitController:update');

            // User


            // Frontend
            $self->app->get('/mdt', function (Request $request, Response $response, array $args) {
                $response = $this->view->render($response, 'mdt.php', []);
                return $response;
            });
            $self->app->get('/dispatcher/{id}', function (Request $request, Response $response, array $args) {
                $response = $this->view->render($response, 'dispatcher.php', ['id' => $args['id']]);
                return $response;
            });
        })->add([$this, 'checkAuth']);
    }

    /**
     * Middleware - Checks users are authenticated for this page
     * @param  Request    $request
     * @param  Response   $response
     * @param  callable   $next
     * @return Response
     */
    public function checkAuth(Request $request, Response $response, callable $next) : Response
    {
        if (is_array($request->getHeader('Accept'))) {
            $json = in_array('application/json', $request->getHeader('Accept'));
        } else {
            $json = stristr($request->getHeader('Accept'), 'application/json') !== NULL;
        }
        if (!isset($_SESSION['user_id'])) {
            return $response->withStatus(401);
        }

        $user = User::where('id', $_SESSION['user_id'])->first();

        if ($user === NULL || $user->session_id !== $_SESSION['user_ref']) {
            return $response->withStatus(401);
        }

        $response = $next($request, $response);
        return $response;
    }
}
