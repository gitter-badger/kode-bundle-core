<?php

namespace KodeCms\KodeBundle\Core\Util;

class Iterable
{
    public static function isEmpty($variable)
    {
        $result = true;

        if (\is_array($variable) && \count($variable) > 0) {
            foreach ($variable as $value) {
                $result = $result && self::isEmpty($value);
            }
        } else {
            $result = empty($variable);
        }

        return $result;
    }

    public static function removeDuplicates(&$array)
    {
        $array = \array_map('unserialize', \array_unique(\array_map('serialize', $array)));
    }

    public static function multiple(array $keys)
    {
        foreach ($keys as $key) {
            if (!\is_array($key)) {
                return false;
            }
        }

        return true;
    }

    public static function multiset(array $keys)
    {
        foreach ($keys as $key) {
            if ($key === null) {
                return false;
            }
        }

        return true;
    }


}