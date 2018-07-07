<?php

namespace KodeCms\KodeBundle\Core\Util;

class Iter
{
    public static function isEmpty($variable): bool
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

    public static function removeDuplicates(&$array): void
    {
        $array = \array_map('\unserialize', \array_unique(\array_map('\serialize', $array)));
    }

    public static function multiple(array $keys): bool
    {
        foreach ($keys as $key) {
            if (!\is_array($key)) {
                return false;
            }
        }

        return true;
    }

    public static function multiset(array $keys): bool
    {
        foreach ($keys as $key) {
            if ($key === null) {
                return false;
            }
        }

        return true;
    }
}