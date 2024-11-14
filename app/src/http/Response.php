<?php
namespace Minuz\Prota\http;


class Response
{
    public static function Response(
        int $code,
        string $warning = 'None',
        string $message = 'None',
        array $data = [],
        ?string $jwt = null,
        array $header = null
    ) {
        header('Content-type: application/json', response_code: $code);
        header("Access-Control-Allow-Origin: *");
        
        if ( $jwt != null ) {
            header("Authorization: Bearer $jwt");
        }

        if ( $header ) {
            foreach ( $header as $key => $value ) {
                $headerString = "$key: $value";
                header($headerString);
            }
        }
        
        $data = array_merge(
            ['Warning' => $warning, 'Status message' => $message], $data
        );
        
        $json = json_encode($data);
        echo $json;
    }



    public static function TestResponse(): void
    {
        header('Content-type: application/json', response_code: 200);
        header("Access-Control-Allow-Origin: *");

        $json = json_encode([
            'Warning' => 'OK',
            'Status message' => 'Hello from Prota!' 
        ]);
        echo $json;
    }
}