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
        // ini_set('session.cookie_secure', 0);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.hash_function', 'sha256');
        ini_set('session.hash_bits_per_character', 5);

        session_name('cad_session');
        session_cache_limiter(false);
        session_start();

        // Make sure the session hasn't expired, and destroy it if it has
        if (self::validateSession()) {
            // Give a 5% chance of the session id changing on any request
            if (rand(1, 100) <= 5) {
                self::regenerateSession();
            }
        } else {
            $_SESSION = [];
            session_destroy();
            session_start();
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
            $self->app->post('/api/incidents/{id}/notes', '\App\Controllers\IncidentController:addNote');

            $self->app->patch('/api/incidents/{id}', '\App\Controllers\IncidentController:update');
            $self->app->post('/api/incidents/{id}/units/{unit_id}', '\App\Controllers\IncidentController:assignUnit');
            $self->app->delete('/api/incidents/{id}/units/{unit_id}', '\App\Controllers\IncidentController:unassignUnit');
            $self->app->post('/api/incidents', '\App\Controllers\IncidentController:create');
            $self->app->delete('/api/incidents/{id}', '\App\Controllers\IncidentController:close');

            // Units
            $self->app->patch('/api/units/{id}', '\App\Controllers\UnitController:update');

            // User

            // Divisions
            $self->app->get('/api/divisions', '\App\Controllers\DivisionController:index');

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

        if (!isset($_SESSION['user_ref'])) {
            return $response->withStatus(401);
        }

        $user = User::where('session_id', $_SESSION['user_ref'])->first();

        if ($user === NULL || $user->session_id !== $_SESSION['user_ref']) {
            return $response->withStatus(401);
        }

        $response = $next($request, $response);
        return $response;
    }

    static protected function validateSession()
    {
        if ( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) ) {
            return false;
        }

        if (isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time()) {
            return false;
        }

        return true;
    }

    static function regenerateSession()
    {
        // If this session is obsolete it means there already is a new id
        if (isset($_SESSION['OBSOLETE']) || $_SESSION['OBSOLETE'] == true) {
            return;
        }

        // Set current session to expire in 10 seconds
        $_SESSION['OBSOLETE'] = true;
        $_SESSION['EXPIRES'] = time() + 30;

        // Create new session without destroying the old one
        session_regenerate_id(false);

        // Grab current session ID and close both sessions to allow other scripts to use them
        $newSession = session_id();
        session_write_close();

        // Set session ID to the new one, and start it back up again
        session_id($newSession);
        session_start();

        // Now we unset the obsolete and expiration values for the session we want to keep
        unset($_SESSION['OBSOLETE']);
        unset($_SESSION['EXPIRES']);
    }
}
