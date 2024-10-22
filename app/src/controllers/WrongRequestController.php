<?php
namespace Minuz\BaseApi\controllers;

use Minuz\BaseApi\http\Request;
use Minuz\BaseApi\http\Response;

class WrongRequestController
{
    public function index(Request $request, Response $response)
    {
        $response::Response(404, 'Wrong path or method, please try again', 'not found');
        return;
    }
}