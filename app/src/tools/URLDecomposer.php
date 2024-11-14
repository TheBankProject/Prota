<?php
namespace Minuz\Prota\Tools;

use Minuz\Prota\tools\Parser;


class URLDecomposer
{
    public static function Detach(string $url, array &$urlInfo = null): void
    {
        $routerPath = $url;
        $urlInfo = [
            'id' => false,
            'query' => false
        ];
        $queryString = parse_url($url, PHP_URL_QUERY);
        
        $requestPath = rtrim(parse_url($url, PHP_URL_PATH), '/');

        $patternId = '~^\/(?:(\w+)(?:\/(\w+))?)?(?:\/?(\d\w+))?$~';
        if ( preg_match($patternId, $requestPath, $matches) && count($matches) > 2) {
            
            $matches = array_reverse($matches);
            $id = $matches[0];
            
            $routerPath = str_replace($id, '{id}', $routerPath);
            $urlInfo['id'] = $id;
        }
    
        if ( ! empty($queryString) ) {
            parse_str($queryString, $query);
            Parser::HydrateNulls($query, false);
            $routerPath = str_replace($queryString, '{query}', $routerPath);
            $urlInfo['query'] = $query;
        }
    
        $urlInfo['path'] = $routerPath;
    }
}