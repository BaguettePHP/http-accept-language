<?php

namespace Teto\HTTP;

/**
 * HTTP `Accept-Language` header parser
 *
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2016 Baguette HQ
 * @license   MIT License
 */
class AcceptLanguageTest extends TestCase
{
    public static $empty_locale =  [
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

    /**
     * @dataProvider dataProviderFor_getLanguage
     */
    public function test_getLanguage($accept_language, $expected)
    {
        $actual = AcceptLanguage::getLanguages($accept_language);

        $this->assertEquals($expected, $actual);
    }

    public function dataProviderFor_getLanguage()
    {
        $e = self::$empty_locale;

        return [
            ['ja',
                'expected' => [
                    100 => [
                        ['language' => 'ja'] + $e,
                    ],
                ],
            ],
            ['ja-Hrkt-JPN;q=0.111111',
                'expected' => [
                     11 => [
                         ['language' => 'ja', 'script' => 'Hrkt', 'region' => 'JP'] + $e,
                     ],
                ],
            ],
            ['ja;q=0.9, en-GB',
                'expected' => [
                    100 => [
                        ['language' => 'en', 'region' => 'GB'] + $e
                     ],
                     90 => [
                         ['language' => 'ja'] + $e
                     ],
                ],
            ],
            ['ja-Kata;q=0.1,en_PCN;q=0.8,zh_HKG;q=0.9,tlh-Latn-US',
                'expected' => [
                    100 => [
                        ['language' => 'tlh', 'script' => 'Latn', 'region' => 'US'] + $e
                    ],
                    90 => [
                        ['language' => 'zh', 'region' => 'HK'] + $e
                    ],
                    80 => [
                        ['language' => 'en', 'region' => 'PN'] + $e
                    ],
                    10 => [
                        ['language' => 'ja', 'script' => 'Kata'] + $e
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderFor_parse
     */
    public function test_parse($tag, $expected)
    {
        $actual = AcceptLanguage::parse($tag);

        $this->assertEquals($expected, $actual);
    }

    public function dataProviderFor_parse()
    {
        $e = self::$empty_locale;

        return [
            ['ja',           'expected' => [1.0, ['language' => 'ja'] + $e]],
            ['ja-JP',        'expected' => [1.0, ['language' => 'ja', 'region' => 'JP'] + $e]],
            ['ja-Hira',      'expected' => [1.0, ['language' => 'ja', 'script' => 'Hira'] + $e]],
            ['ja;q=1.0',     'expected' => [1.0, ['language' => 'ja'] + $e]],
            ['ja; q=1.0',    'expected' => [1.0, ['language' => 'ja'] + $e]],
            ['ja-JP;q=1',    'expected' => [1.0, ['language' => 'ja', 'region' => 'JP'] + $e]],
            ['ja;q=1.00',    'expected' => [1.0, ['language' => 'ja'] + $e]],
            ['*',            'expected' => [1.0, ['language' => '*'] + $e]],
            ['*-Hant;q=0.1', 'expected' => [0.1, ['language' => '*',  'script' => 'Hant'] + $e]],
            ['zh-*-TW',      'expected' => [1.0, ['language' => 'zh', 'region' => 'TW'] + $e]],
            ['xx',           'expected' => [1.0, ['language' => 'xx'] + $e]],
        ];
    }

    /**
     * @dataProvider dataProviderFor_detect
     */
    public function test_detect($accept_language, $default, $expected)
    {
        $known_languages = ['ja', 'en', 'es', 'ko'];
        $strategy = function (array $locale) use ($known_languages) {
            $is_wildcard = isset($locale['language']) && $locale['language'] === '*';
            if (empty($locale['language']) && !$is_wildcard) {
                return null;
            }
            if ($is_wildcard || $locale['language'] === 'zh') {
                if (!empty($locale['region']) && $locale['region'] == 'TW') {
                    return 'zh_tw';
                }
                if (!empty($locale['script']) && $locale['script'] == 'Hant') {
                    return 'zh_tw';
                }
                if ($locale['language'] === 'zh') {
                    return 'zh_cn';
                }
            }

            if (in_array($locale['language'], $known_languages)) {
                return $locale['language'];
            }

            return null;
        };

        $actual = AcceptLanguage::detect($strategy, $default, $accept_language);

        $this->assertEquals($expected, $actual);
    }

    public function dataProviderFor_detect()
    {
        return [
            [
                'ja-Kata;q=0.1,en_PCN;q=1,zh_HKG;q=0.9,tlh-Latn-US',
                'default' => 'ja',
                'expected' => 'en',
            ],
            [
                'ja-Kata;q=0.1,en_PCN;q=0.8,zh_HKG;q=0.9,tlh-Latn-US',
                'default' => 'ja',
                'expected' => 'zh_cn',
            ],
            [
                'en-Kata;q=0.1,en_PCN;djfiasjdflsakdjflksajflas,zh_HKG;q=2000,tlh-Latn-US',
                'default' => 'ja',
                'expected' => 'en',
            ],
        ];
    }
}
