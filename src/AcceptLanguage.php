<?php
namespace Teto\HTTP;
use Locale;

/**
 * HTTP `Accept-Language` header parser
 *
 * @package   Teto\HTTP*
 * @copyright Copyright (c) 2014 USAMI Kenta
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   MIT License
 */
class AcceptLanguage
{
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
     * @return array
     */
    public static function getLanguages($http_accept_language)
    {
        $tags = array_filter(array_map('self::parse', explode(',', $http_accept_language)));

        $grouped_tags = array();
        foreach ($tags as $tag) {
            list($q, $t) = $tag;
            $intq = (int)($q * 10);
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
     * @return array  (float:quality, array:locale)
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

        $locale = Locale::parseLocale($lang_tag);

        if ($is_wildcard) {
            $locale['language'] = '*';
        }

        return array($q, $locale);
    }

    /**
     * @param  array $a (float:quality, array:locale)
     * @param  array $b (float:quality, array:locale)
     * @return bool
     */
    private static function sort_tags(array $a, array $b)
    {
        if ($a[0] === $b[0]) {
            return 0;
        }

        return ($a[0] < $b[0]) ? -1 : 1;
    }
}
