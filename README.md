HTTP Accept-Language
====================

![Build Status](https://travis-ci.org/zonuexe/php-http-accept-language.svg)](https://travis-ci.org/zonuexe/php-http-accept-language)

HTTP `Accept-Language` header parser for PHP

Description
-----------

``

Requirements
------------

 * PHP (5.4+)
   * `ext/intl`

Usage
-----

see `tests/public/greeting.php`.

API
---

 * `Teto\HTTP\AcceptLanguage::get()`
 * `Teto\HTTP\AcceptLanguage::getLanguages()`
 * `Teto\HTTP\AcceptLanguage::parse()`
 * `Teto\HTTP\AcceptLanguage::detect()`

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