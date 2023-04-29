<?php

/**
 * Sample page for HTTP `Accept-Language` header parser
 *
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2016 Baguette HQ
 * @license   MIT License
 */

include_once __DIR__ . '/../../vendor/autoload.php';

$greetings = [
    'tlh' => "nuqneH",
    'zh'  => "你好",
    'ja'  => "こんにちは",
    'en'  => "Hello",
];

$greeting = '';

$title = '';

foreach (\Teto\HTTP\AcceptLanguage::get() as $locale) {
    if (isset($greetings[$locale['language']])) {
        $greeting = $greetings[$locale['language']];
        $title = "Accept-Language: " . implode('-', $locale);
        break;
    }
}

$greeting = $greeting ?: 'Yo';
?>
<title><?= htmlspecialchars($title, ENT_HTML5, 'UTF-8') ?></title>
<p><?= htmlspecialchars($greeting, ENT_HTML5, 'UTF-8') ?></p>
<code><pre>
<?= htmlspecialchars(json_encode(
            \Teto\HTTP\AcceptLanguage::getLanguages($_SERVER['HTTP_ACCEPT_LANGUAGE']),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '',
            ENT_HTML5, 'UTF-8'
        ) ?>
</pre></code>
