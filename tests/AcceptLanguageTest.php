<?php
namespace Teto\HTTP;

/**
 * HTTP `Accept-Language` header parser
 *
 * @package   Teto\HTTP*
 * @copyright Copyright (c) 2014 USAMI Kenta
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   MIT License
 */
class AcceptLanguageTest extends TestCase
{
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
        return [
            ['ja',
                'expected' => [
                    10 => [['language' => 'ja']],
                ],
            ],
            ['ja-Hrkt-JPN;q=0.111111',
                'expected' => [
                     1 => [
                         ['language' => 'ja', 'script' => 'Hrkt', 'region' => 'JP'],
                     ],
                ],
            ],
            ['ja;q=0.9, en-GB',
                'expected' => [
                    10 => [
                        ['language' => 'en', 'region' => 'GB']
                     ],
                     9 => [
                         ['language' => 'ja']
                     ],
                ],
            ],
            ['ja-Kata;q=0.1,en_PCN;q=0.8,zh_HKG;q=0.9,tlh-Latn-US',
                'expected' => [
                    10 => [
                        ['language' => 'tlh', 'script' => 'Latn', 'region' => 'US']
                    ],
                    9 => [
                        ['language' => 'zh', 'region' => 'HK']
                    ],
                    8 => [
                        ['language' => 'en', 'region' => 'PN']
                    ],
                    1 => [
                        ['language' => 'ja', 'script' => 'Kata']
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
        return [
            ['ja',           'expected' => [1.0, ['language' => 'ja']]],
            ['ja-JP',        'expected' => [1.0, ['language' => 'ja', 'region' => 'JP']]],
            ['ja-Hira',      'expected' => [1.0, ['language' => 'ja', 'script' => 'Hira']]],
            ['ja;q=1.0',     'expected' => [1.0, ['language' => 'ja']]],
            ['ja; q=1.0',    'expected' => [1.0, ['language' => 'ja']]],
            ['ja-JP;q=1',    'expected' => [1.0, ['language' => 'ja', 'region' => 'JP']]],
            ['ja;q=1.00',    'expected' => [1.0, ['language' => 'ja']]],
            ['*',            'expected' => [1.0, ['language' => '*']]],
            ['*-Hant;q=0.1', 'expected' => [0.1, ['language' => '*',  'script' => 'Hant']]],
            ['xx',           'expected' => [1.0, ['language' => 'xx']]],
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
