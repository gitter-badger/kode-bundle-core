<?php

namespace KodeCms\KodeBundle\Core\Util;

class Generator
{
    public const PASS_LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
    public const PASS_UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const PASS_DIGITS = '0123456789';
    public const PASS_SYMBOLS = '!@#$%^&*()_-=+;:.,?';

    public const RAND_BASIC = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const RAND_EXTENDED = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-=+;:,.?';

    private $sets = [];

    public function generate($length = 20): string
    {
        $all = '';
        $password = '';
        foreach ($this->sets as $set) {
            $password .= $set[$this->tweak(\str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for ($i = 0; $i < $length - \count($this->sets); $i++) {
            $password .= $all[$this->tweak($all)];
        }

        return \str_shuffle($password);
    }

    public function tweak($array)
    {
        if (\function_exists('random_int')) {
            return \random_int(0, \count($array) - 1);
        }
        if (\function_exists('mt_rand')) {
            return \mt_rand(0, \count($array) - 1);
        }

        return \array_rand($array);
    }

    public function useLower(): Generator
    {
        $this->sets['lower'] = self::PASS_LOWERCASE;

        return $this;
    }

    public function useUpper(): Generator
    {
        $this->sets['upper'] = self::PASS_UPPERCASE;

        return $this;
    }

    public function useDigits(): Generator
    {
        $this->sets['digits'] = self::PASS_DIGITS;

        return $this;
    }

    public function useSymbols(): Generator
    {
        $this->sets['symbols'] = self::PASS_SYMBOLS;

        return $this;
    }

    public static function getRandomString($length = 20, $chars = self::RAND_BASIC): string
    {
        return \substr(\str_shuffle(\str_repeat($chars, (int)\ceil((int)($length / \strlen($chars))))), 1, $length);
    }

    public static function getUniqueId($length = 20): string
    {
        try {
            return \bin2hex(random_bytes($length));
        } catch (\Exception $exception) {
            return self::getRandomString($length);
        }
    }
}