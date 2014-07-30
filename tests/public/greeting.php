<?php
/**
 * Sample page for HTTP `Accept-Language` header parser
 *
 * @package   Teto\HTTP\Sample*
 * @copyright Copyright (c) 2014 USAMI Kenta
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   MIT License
 */
namespace Teto\HTTP\Sample;
include_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

/** @var $greetings array */
$greetings = array(
    'tlh' => "nuqneH",
    'zh'  => "你好",
    'ja'  => "こんにちは",
    'en'  => "Hello",
);

/** @var $greeting string */
$greeting = '';
foreach (\Teto\HTTP\AcceptLanguage::get() as $locale) {
    if (isset($greetings[$locale['language']])) {
        $greeting = $greetings[$locale['language']];
        $title = "Accept-Language: " . implode('-', $locale);
        break;
    }
}

$greeting = $greeting ?: 'Yo';

/** @var $title string */

?>
<title><?= htmlspecialchars($title, ENT_HTML5, 'UTF-8') ?></title>
<p><?= htmlspecialchars($greeting, ENT_HTML5, 'UTF-8') ?></p>
<code><pre>
<?= htmlspecialchars(json_encode(
            \Teto\HTTP\AcceptLanguage::getLanguages($_SERVER['HTTP_ACCEPT_LANGUAGE']),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ENT_HTML5, 'UTF-8'
        ) ?>
</pre></code>
