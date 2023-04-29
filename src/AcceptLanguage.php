<?php

namespace Teto\HTTP;

use function array_filter;
use function array_map;
use function explode;
use function is_numeric;
use function krsort;
use function round;
use function str_replace;
use function strlen;
use function strpos;
use function substr;
use const PHP_ROUND_HALF_UP;
use const SORT_NUMERIC;

/**
 * HTTP `Accept-Language` header parser
 *
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2016 Baguette HQ
 * @license   MIT License
 * @phpstan-type accept_language_parsed array{
 *     language: string,
 *     script: string,
 *     region: string,
 *     variant1: string,
 *     variant2: string,
 *     variant3: string,
 *     private1: string,
 *     private2: string,
 *     private3: string
 * }
 * @phpstan-type accept_language_sparse array{
 *     language: string,
 *     script?: string,
 *     region?: string,
 *     variant1?: string,
 *     variant2?: string,
 *     variant3?: string,
 *     private1?: string,
 *     private2?: string,
 *     private3?: string
 * }
 */
class AcceptLanguage
{
    /**
     * @param  string $http_accept_language
     * @phpstan-return list<accept_language_parsed>
     */
    public static function get($http_accept_language = '')
    {
        if (!$http_accept_language) {
            $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        }

        $languages = [];
        foreach (self::getLanguages($http_accept_language) as $quality_group) {
            foreach ($quality_group as $lang) {
                $languages[] = $lang;
            }
        }

        return $languages;
    }

    /**
     * @template TReturn
     * @template TDefault
     * @param callable(array<string, string>): TReturn $strategy
     * @param  string   $http_accept_language
     * @phpstan-param TDefault $default
     * @phpstan-return TReturn|TDefault~null
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
     * @param non-empty-string $http_accept_language
     * @param positive-int $resolution Resolution of `q`(quality) value
     * @return array<int, list<accept_language_parsed>>
     */
    public static function getLanguages($http_accept_language, $resolution = 100)
    {
        $tags = array_filter(array_map(self::class . '::parse', explode(',', $http_accept_language)));

        $grouped_tags = [];
        foreach ($tags as [$q, $tag]) {
            $intq = (int)round($q * $resolution, 0, PHP_ROUND_HALF_UP);
            if (isset($grouped_tags[$intq])) {
                $grouped_tags[$intq][] = $tag;
            } else {
                $grouped_tags[$intq] = [$tag];
            }
        }
        krsort($grouped_tags, SORT_NUMERIC);

        return $grouped_tags;
    }

    /**
     * @param  string $locale_str LanguageTag (with quality)
     * @link   http://php.net/manual/locale.parselocale.php
     * @return array  2-tuple(float:quality, array:locale)
     * @phpstan-return array{}|array{float, accept_language_parsed}
     */
    public static function parse($locale_str)
    {
        $split = array_map('trim', explode(';', $locale_str, 2));
        if (!isset($split[0]) || strlen($split[0]) === 0) {
            return [];
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
                return [];
            }
        } else {
            $q = 1.0;
        }

        /** @phpstan-var accept_language_sparse */
        $locale = \Locale::parseLocale($lang_tag);

        if ($is_wildcard) {
            $locale['language'] = '*';
        }

        return array($q, self::fillLocaleArrayKey($locale));
    }

    /**
     * @phpstan-param accept_language_sparse $locale
     * @phpstan-return accept_language_parsed
     * @link   http://php.net/manual/locale.composelocale.php
     */
    private static function fillLocaleArrayKey(array $locale): array
    {
        return $locale + [
            'language' => '',
            'script'   => '',
            'region'   => '',
            'variant1' => '',
            'variant2' => '',
            'variant3' => '',
            'private1' => '',
            'private2' => '',
            'private3' => '',
        ];
    }
}
