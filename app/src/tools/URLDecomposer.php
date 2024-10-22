<?php
namespace Minuz\BaseApi\Tools;

use Minuz\BaseApi\tools\Parser;


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
        
        $requestPath = parse_url($url, PHP_URL_PATH);
        $patternId = '~\/[\w]+\/[\w]+\/([\S]+)~';
        if ( preg_match($patternId, $requestPath, $matches) ) {
            $id = $matches[1];
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