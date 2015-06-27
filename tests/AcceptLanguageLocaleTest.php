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
class AcceptLanguage_ObjectTest extends TestCase
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

    public function test()
    {
        $actual = new AcceptLanguageLocale([]);

        $this->assertSame(self::$empty_locale, $actual->getAsArray());
        foreach (self::$empty_locale as $l => $v) {
            $this->assertSame($v, $actual[$l]);
            $this->assertSame($v, $actual->$l);
            $this->assertTrue(isset($actual[$l]));
            $this->assertTrue(empty($actual[$l]));
            $this->assertTrue(isset($actual->$l));
            $this->assertTrue(empty($actual->$l));
        }

        foreach ($actual as $a => $b) {
            $this->assertSame(self::$empty_locale[$a], $b);
        }
    }

    /**
     * @expectedException \LogicException
     */
    public function test_set()
    {
        $actual = new AcceptLanguageLocale([]);
        $actual->error = '／(^o^)＼';
    }

    /**
     * @expectedException \LogicException
     */
    public function test_unset()
    {
        $actual = new AcceptLanguageLocale([]);
        unset($actual->error);
    }

    /**
     * @expectedException \LogicException
     */
    public function test_offsetSet()
    {
        $actual = new AcceptLanguageLocale([]);
        $actual['error'] = '／(^o^)＼';
    }

    /**
     * @expectedException \LogicException
     */
    public function test_offsetUnset()
    {
        $actual = new AcceptLanguageLocale([]);
        unset($actual['error']);
    }
}
