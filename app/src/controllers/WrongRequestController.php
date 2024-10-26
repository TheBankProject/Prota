<?php
namespace Minuz\Prota\controllers;

use Minuz\Prota\http\Request;
use Minuz\Prota\http\Response;

class WrongRequestController
{
    public function index(Request $request, Response $response)
    {
        $response::Response(404, 'Wrong path or method, please try again', 'not found');
        return;
    }
}