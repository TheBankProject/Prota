<?php

    namespace Minuz\Prota\Core;

    use Minuz\Prota\exceptions\RouteNotFound;
    use Minuz\Prota\http\Request;
    use Minuz\Prota\http\Response;
    use Minuz\Prota\http\Router;
    use Minuz\Prota\tools\URLDecomposer;

class Core
{
    public static function dispatch(Router $router)
    {
        $prefixController = 'Minuz\\Prota\\controllers\\';

        $url = Request::path();
        URLDecomposer::Detach($url, $urlData);
        
        $route = $urlData['path'];
        
        if ( Request::path() == '/' ) {
            Response::TestResponse();
            return;
        }
        try {
            [$controllerClass, $action] = $router->resolve($route, Request::method());
        }
        catch (RouteNotFound) {
            $controllerClass = $prefixController . 'WrongRequestController';
            $controller = new $controllerClass();
            $controller->index(new Request, new Response);
        
            return;
        }
        
        $controllerClass = $prefixController . $controllerClass;
        $controller = new $controllerClass();

        $controller->$action(new Request, new Response, $urlData['id'], $urlData['query']);

        return;

    }
}