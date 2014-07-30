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
        return array(
            array('ja',
                'expected' => array(
                    10 => array(array('language' => 'ja')),
                ),
            ),
            array('ja-Hrkt-JPN;q=0.111111',
                'expected' => array(
                     1 => array(
                         array('language' => 'ja', 'script' => 'Hrkt', 'region' => 'JP'),
                     ),
                ),
            ),
            array('ja;q=0.9, en-GB',
                'expected' => array(
                    10 => array(
                        array('language' => 'en', 'region' => 'GB')
                    ),
                     9 => array(
                         array('language' => 'ja')
                     ),
                ),
            ),
            array('ja-Kata;q=0.1,en_PCN;q=0.8,zh_HKG;q=0.9,tlh-Latn-US',
                'expected' => array(
                    10 => array(
                        array('language' => 'tlh', 'script' => 'Latn', 'region' => 'US')
                    ),
                    9 => array(
                        array('language' => 'zh', 'region' => 'HK')
                    ),
                    8 => array(
                        array('language' => 'en', 'region' => 'PN')
                    ),
                    1 => array(
                        array('language' => 'ja', 'script' => 'Kata')
                    ),
                ),
            ),
        );
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
        return array(
            array('ja',           'expected' => array(1.0, array('language' => 'ja'))),
            array('ja-JP',        'expected' => array(1.0, array('language' => 'ja', 'region' => 'JP'))),
            array('ja-Hira',      'expected' => array(1.0, array('language' => 'ja', 'script' => 'Hira'))),
            array('ja;q=1.0',     'expected' => array(1.0, array('language' => 'ja'))),
            array('ja; q=1.0',    'expected' => array(1.0, array('language' => 'ja'))),
            array('ja-JP;q=1',    'expected' => array(1.0, array('language' => 'ja', 'region' => 'JP'))),
            array('ja;q=1.00',    'expected' => array(1.0, array('language' => 'ja'))),
            array('*',            'expected' => array(1.0, array('language' => '*'))),
            array('*-Hant;q=0.1', 'expected' => array(0.1, array('language' => '*',  'script' => 'Hant'))),
            array('xx',           'expected' => array(1.0, array('language' => 'xx'))),
        );
    }
}
