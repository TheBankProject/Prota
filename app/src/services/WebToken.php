<?php
namespace Minuz\BaseApi\services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();
class WebToken
{
    public static function Login(array|bool $auth)
    {
        $token = JWT::encode($auth, $_ENV['JWT_KEY'], 'HS256');
        return;
    }
    


    public static function SessionLogin()
    {
        try {
            $info = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch (\UnexpectedValueException $e) {
            return;
        }

        return;
    }
}