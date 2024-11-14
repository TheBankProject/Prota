<?php
require_once __DIR__ . '/../src/config/bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Minuz\Prota\Core\Core;
use Minuz\Prota\http\Router;

use Minuz\Prota\controllers\AccountController;
use Minuz\Prota\controllers\LoanController;

$router = new Router(); 
$router->registryControllersRoutes([
    AccountController::class,
    LoanController::class
]);

Core::dispatch($router);