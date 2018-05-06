<?php

namespace KodeCms\KodeBundle\Core\Util;

use Symfony\Component\HttpFoundation\Request;

class Helper
{
    public static function swap(&$foo, &$bar)
    {
        $tmp = $foo;
        $foo = $bar;
        $bar = $tmp;
    }

    public static function useHttps(Request $request)
    {
        $https = false;
        if ($request->server->has('HTTPS') && 'on' === $request->server->get('HTTPS')) {
            $https = true;
        } elseif ($request->server->has('SERVER_PORT') && 443 === (int)$request->server->get('SERVER_PORT')) {
            $https = true;
        } elseif ($request->server->has('HTTP_X_FORWARDED_SSL') && 'on' === $request->server->get('HTTP_X_FORWARDED_SSL')) {
            $https = true;
        } elseif ($request->server->has('HTTP_X_FORWARDED_PROTO') && 'https' === $request->server->get('HTTP_X_FORWARDED_PROTO')) {
            $https = true;
        }

        return $https;
    }

    public static function getSchema(Request $request)
    {
        if (self::useHttps($request)) {
            return 'https://';
        }

        return 'http://';
    }
}