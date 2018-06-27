<?php

namespace KodeCms\KodeBundle\Core\Util;

class Text
{
    // @formatter:off
    /**
     * Cyrillic mapping.
     *
     * @var array
     */
    public const CYRMAP = [
        'е', 'ё', 'ж', 'х', 'ц', 'ч', 'ш', 'щ', 'ю', 'я',
        'Е', 'Ё', 'Ж', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ю', 'Я',
        'а', 'б', 'в', 'г', 'д', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ъ', 'ы', 'ь', 'э',
        'А', 'Б', 'В', 'Г', 'Д', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Ъ', 'Ы', 'Ь', 'Э'
    ];

    /**
     * Latin mapping.
     *
     * @var array
     */
    public const LATMAP = [
        'ye', 'ye', 'zh', 'kh', 'ts', 'ch', 'sh', 'shch', 'yu', 'ya',
        'Ye', 'Ye', 'Zh', 'Kh', 'Ts', 'Ch', 'Sh', 'Shch', 'Yu', 'Ya',
        'a', 'b', 'v', 'g', 'd', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ʺ', 'y', '–', 'e',
        'A', 'B', 'V', 'G', 'D', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'ʺ', 'Y', '–', 'E'
    ];
    // @formatter:on

    public static function toCamelCase($string, $lowFirst = true): string
    {
        if ($lowFirst) {
            return \preg_replace('~\s+~', '', \lcfirst(\ucwords(\strtolower(\str_replace('_', ' ', $string)))));
        }

        return \preg_replace('~\s+~', '', \ucwords(\strtolower(\str_replace('_', ' ', $string))));
    }

    public static function fromCamelCase($string, $separator = '_'): string
    {
        return \strtolower(\preg_replace('/(?!^)[[:upper:]]+/', $separator.'$0', $string));
    }

    public static function cleanText($text): string
    {
        return \html_entity_decode(self::oneSpace(\str_replace(' ?', '', \mb_convert_encoding(\strip_tags($text), 'UTF-8', 'UTF-8'))));
    }

    public static function oneSpace($text): string
    {
        return \preg_replace('/\s+/S', ' ', $text);
    }

    /**
     * Transliterates cyrillic text to latin.
     *
     * @param  string $text cyrillic text
     *
     * @return string latin text
     */
    public static function translit2($text): string
    {
        return \str_replace(self::CYRMAP, self::LATMAP, $text);
    }

    /**
     * Transliterates latin text to cyrillic.
     *
     * @param  string $text latin text
     *
     * @return string cyrillic text
     */
    public static function translit4($text): string
    {
        return \str_replace(self::LATMAP, self::CYRMAP, $text);
    }
}
