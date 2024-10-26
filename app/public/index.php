<?php
require_once __DIR__ . '/../src/config/bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Minuz\Prota\Core\Core;
use Minuz\Prota\http\Router;


$router = new Router(); 
$router->registryControllersRoutes([
]);

Core::dispatch($router);