<?php
namespace Minuz\BaseApi\services;

use CacheExpires;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();
class WebToken
{
    public static function login(array|bool $auth): void
    {
        $acessToken = JWT::encode($auth, $_ENV['JWT_KEY'], 'HS256');
        $_SESSION['acessToken'] = $acessToken;
        session_cache_expire(CacheExpires::fast);
        return; 
    }



    public static function Signup(array|bool $auth)
    {

    }



    public static function getInfo(string $token): string
    {
        try {
            $auth = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch (\UnexpectedValueException $e) {
            return;
        }

        return $auth->;
    }
}