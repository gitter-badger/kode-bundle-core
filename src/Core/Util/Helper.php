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

    protected static function checkHttps(Request $request)
    {
        return $request->server->has('HTTPS') && 'on' === $request->server->get('HTTPS');
    }

    protected static function checkServerPort(Request $request)
    {
        return $request->server->has('SERVER_PORT') && 443 === (int)$request->server->get('SERVER_PORT');
    }

    protected static function checkHttpXForwardedSsl(Request $request)
    {
        return $request->server->has('HTTP_X_FORWARDED_SSL') && 'on' === $request->server->get('HTTP_X_FORWARDED_SSL');
    }

    protected static function checkHttpXForwardedProto(Request $request)
    {
        return $request->server->has('HTTP_X_FORWARDED_PROTO') && 'https' === $request->server->get('HTTP_X_FORWARDED_PROTO');
    }

    public static function useHttps(Request $request)
    {
        $checks = ['HTTPS', 'SERVER_PORT', 'HTTP_X_FORWARDED_SSL', 'HTTP_X_FORWARDED_PROTO'];
        foreach ($checks as $check) {
            if (\call_user_func(\sprintf('check%s', Text::toCamelCase($check)), $request)) {
                return true;
            }
        }

        return false;
    }

    public static function getSchema(Request $request)
    {
        return self::useHttps($request) ? 'https://' : 'http://';
    }
}