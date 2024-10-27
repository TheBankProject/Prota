<?php
namespace Minuz\Prota\apis;

class GovAPI
{
    public static function checkData(array $userInfo)
    {
        $ch = curl_init('http://localhost:8080/check');
        curl_setopt_array($ch, [
            CURLOPT_POSTFIELDS => $userInfo,
            CURLOPT_POST => true
        ]);
    }
}