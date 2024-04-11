<?php

define('BASE_PATH', str_replace('\\', '/', rtrim(__DIR__, '/')) . '/../');

require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'Base/Autoloader.php';
require_once BASE_PATH . 'App/Router.php';
require_once BASE_PATH . 'Base/ServiceLoader.php';

$response = new Base\Response();

use App\Router;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$router = new Router();

require BASE_PATH . 'routes.php';

$router->route($uri, $method);

$response->send();