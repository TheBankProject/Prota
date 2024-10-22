<?php
namespace Minuz\BaseApi\exceptions;


class RouteNotFound extends \Exception
{
    protected $message = 'Route not found';
}