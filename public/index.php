<?php
/**
 * This is the entrypoint of the whole application, where this file is the only thing accessible publicly since it's under public directory
 * Application is a bootstrap to the whole web Application, where you have access to the core methods such as using router to declare routes
 *
 * Routes must be also declared here, you can refer to Router class for more examples and description
 */

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Needed in development to show errors on PHP pages, perhaps later we can use external package to handle showing better errors
if (isset($_ENV['ENV']) && $_ENV === 'dev') {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

// Here we setup the main config that will be passed to the Application instance beside mainly the DB configuration
// Currently this will use only MySQL as a main DB, later we can integrate an ORM or maybe do our own?!
$config = [
  'db' => [
    'dsn' => $_ENV['DB_DSN'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
  ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'showHome']);

$app->router->get('/contact', [SiteController::class, 'showContact']);
$app->router->post('/contact', [SiteController::class, 'handleContact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

// Then magic method which run the whole app
$app->run();
