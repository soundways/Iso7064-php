# ISO 7064

Soundways Iso7064 provides an implementation of ISO 7064:1983 Mod 37, 36, intended primarily for use in calculating the check character for [GRid standard 2.1](https://ifpi.org/downloads/GRid_Standard_v2_1.pdf).

This requires PHP 7.1

## Installation

Using [Composer](https://getcomposer.org), require this package in the root directory of your project.

```bash
$ composer require 'soundways/iso7064'
```

### Dependencies

`ext-mbstring` is required for `mb_strlen`.  If `ext-mbstring` is unavailable for any reason, `symfony\polyfill-mbstring` is required in its place.

## Quickstart

Calculate a check character using a GRid object:

```php
use Soundways\Iso7064\GRid;

$grid = new GRid('A1-2425G-ABC1234002');

$grid->encode();

$grid->getDelimitedGRid();

// returns 'A1-2425G-ABC1234002-M'
```

## Reference

The namespace contains the `Mod3736` class for general-purpose encoding within the ISO standard and the `GRid` class which enforces GRid specific requirements and includes some helper functions.

## Addendum

[With respect to Andre Catita's implementation of ISO 7064 Mod 11, 2](http://andrecatita.com/code-snippets/iso-7064-mod-112-php/).

