HTTP Accept-Language
====================

[![Package version](http://img.shields.io/packagist/v/zonuexe/http-accept-language.svg?style=flat)](https://packagist.org/packages/zonuexe/http-accept-language)
[![Build Status](https://github.com/BaguettePHP/http-accept-language/actions/workflows/test.yml/badge.svg?branch=master)](https://github.com/BaguettePHP/http-accept-language/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/zonuexe/http-accept-language.svg)](https://packagist.org/packages/zonuexe/http-accept-language)

`Teto\HTTP\AcceptLanguage` is HTTP `Accept-Language` header parser based on PHP [`Locale`][Locale] module.

[Locale]: https://www.php.net/Locale

## Future scope

This package was designed ten years ago and is considered legacy due to its global dependencies. Over time I will provide a new package as part of the [Hakone] project.

[Hakone]: https://github.com/hakonephp

Requirements
------------

 * PHP (7.2+)
   * `ext/intl`

Installation
------------

```
composer require zonuexe/http-accept-language
```

Usage
-----

see `tests/public/greeting.php`.

API
---

 * `Teto\HTTP\AcceptLanguage::detect()`
 * `Teto\HTTP\AcceptLanguage::get()`
 * `Teto\HTTP\AcceptLanguage::getLanguages()`
 * `Teto\HTTP\AcceptLanguage::parse()`

Features
--------

 * Accepts `*`(wildcard) tag
   * `*-Hant-*` → `{language: '*', script: 'Hant'}`
   * `zh-*-TW` → `{language: 'zh', region: 'TW'}`

Reference
---------

 * [HTTP/1.1: Header Field Definitions #14.4 Accept-Language](http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4)
 * [RFC 4647 - Matching of Language Tags](http://tools.ietf.org/html/rfc4647)
 * [RFC 5646 - Tags for Identifying Languages](http://tools.ietf.org/html/rfc5646)

Copyright
---------

> HTTP `Accept-Language` header parser for PHP
>
> Copyright (c) 2016 USAMI Kenta / Baguette HQ
