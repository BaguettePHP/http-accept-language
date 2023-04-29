<?php

namespace Teto\HTTP;

/**
 * HTTP `Accept-Language` header parser
 *
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2016 Baguette HQ
 * @license   MIT License
 */
class AcceptLanguage
{
    /** @throws \BadMethodCallException */
    private function __construct() { throw new \BadMethodCallException(); }

    /**
     * @param  string $http_accept_language
     * @return array
     */
    public static function get($http_accept_language = '')
    {
        if (!$http_accept_language) {
            $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        }

        $languages = array();
        foreach (self::getLanguages($http_accept_language) as $q => $quality_group) {
            foreach ($quality_group as $lang) {
                $languages[] = $lang;
            }
        }

        return $languages;
    }

    /**
     * @param  callable $strategy
     * @param  string   $http_accept_language
     * @param  mixed    $default
     * @return mixed
     */
    public static function detect(callable $strategy, $default, $http_accept_language = '')
    {
        if (!$http_accept_language) {
            $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        }

        foreach (self::get($http_accept_language) as $lang) {
            $result = $strategy($lang);
            if (!empty($result)) {
                return $result;
            }
        }

        return $default;
    }

    /**
     * @param  string $http_accept_language
     * @param  int    $resolution Resolution of `q`(quality) value
     * @return array
     */
    public static function getLanguages($http_accept_language, $resolution = 100)
    {
        $tags = array_filter(array_map(self::class . '::parse', explode(',', $http_accept_language)));

        $grouped_tags = array();
        foreach ($tags as $tag) {
            list($q, $t) = $tag;
            $intq = (int)round($q * $resolution, 0, PHP_ROUND_HALF_UP);
            if (!isset($grouped_tags[$intq])) {
                $grouped_tags[$intq] = array();
            }
            $grouped_tags[$intq][] = $t;
        }
        krsort($grouped_tags, SORT_NUMERIC);

        return $grouped_tags;
    }

    /**
     * @param  string $locale_str LanguageTag (with quality)
     * @return array  2-tuple(float:quality, array:locale)
     * @link   http://php.net/manual/locale.parselocale.php
     */
    public static function parse($locale_str)
    {
        $split = array_map('trim', explode(';', $locale_str, 2));
        if (!isset($split[0]) || strlen($split[0]) === 0) {
            return array();
        }

        if (strpos($split[0], '*') === 0) {
            $lang_tag = str_replace('*', 'xx', $split[0]);
            $is_wildcard = true;
        } else {
            $lang_tag = $split[0];
            $is_wildcard = false;
        }

        $lang_tag = str_replace('-*', '', $lang_tag);

        if (isset($split[1]) && strpos($split[1], 'q=') === 0) {
            $q = (float)substr($split[1], 2);

            if (!is_numeric($q) || $q <= 0 || 1 < $q) {
                return array();
            }
        } else {
            $q = 1.0;
        }

        $locale = \Locale::parseLocale($lang_tag);

        if ($is_wildcard) {
            $locale['language'] = '*';
        }

        return array($q, new AcceptLanguageLocale($locale));
    }

    /**
     * @param  array $a 2-tuple(float:quality, array:locale)
     * @param  array $b 2-tuple(float:quality, array:locale)
     * @return int
     */
    private static function sort_tags(array $a, array $b)
    {
        if ($a[0] === $b[0]) {
            return 0;
        }

        return ($a[0] < $b[0]) ? -1 : 1;
    }
}
