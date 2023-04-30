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

 * [RFC 9110 - HTTP Semantics #12.5.4. Accept-Language][rfc9110-accept-language]
 * [RFC 4647 - Matching of Language Tags][rfc4647]
 * [RFC 5646 - Tags for Identifying Languages][rfc5646]

[rfc9110-accept-language]: https://www.rfc-editor.org/rfc/rfc9110.html#name-accept-language
[rfc4647]: https://datatracker.ietf.org/doc/html/rfc4647
[rfc5646]: https://datatracker.ietf.org/doc/html/rfc5646

Copyright
---------

> **HTTP Accept-Language header parser for PHP**
>
> Copyright (c) 2016 Baguette HQ / USAMI Kenta
>
> MIT License
>
> Permission is hereby granted, free of charge, to any person obtaining
> a copy of this software and associated documentation files (the
> "Software"), to deal in the Software without restriction, including
> without limitation the rights to use, copy, modify, merge, publish,
> distribute, sublicense, and/or sell copies of the Software, and to
> permit persons to whom the Software is furnished to do so, subject to
> the following conditions:
>
> The above copyright notice and this permission notice shall be
> included in all copies or substantial portions of the Software.
>
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
> EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
> MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
> NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
> LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
> OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
> WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

