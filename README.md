HTTP Accept-Language
====================

[![Build Status](https://travis-ci.org/zonuexe/php-http-accept-language.svg?branch=v0.2.0)](https://travis-ci.org/zonuexe/php-http-accept-language)
[![Downloads this Month](https://img.shields.io/packagist/dm/zonuexe/http-accept-language.svg)](https://packagist.org/packages/zonuexe/http-accept-language)

Description
-----------

`Teto\HTTP\AcceptLanguage` is HTTP `Accept-Language` header parser based on PHP `Locale` module.

Requirements
------------

 * PHP (5.4+)
   * `ext/intl`

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
> Copyright (c) 2014 USAMI Kenta
