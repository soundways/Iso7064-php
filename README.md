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

Iso7064 uses type hinting which requires a PHP version ^7.1  

## Quickstart

Calculate a check character using a GRid object:

```php
use Soundways\Iso7064\GRid;

$grid = new GRid('A1-2425G-ABC1234002');

$grid->encode();

// returns 'A1-2425G-ABC1234002-M'
$grid->getDelimitedGRid();
```

## Reference

The namespace contains the `Mod3736` class for general-purpose encoding within the ISO standard and the `GRid` class which enforces GRid specific requirements and includes some helper functions.

### Mod

`Mod` is an abstract class providing general functionality for encoding and verifying check characters using ISO 7064, regardless of modulus.  Codes will be stripped of non-alphanumeric characters and stored in the object's `$code` attribute.

- `function encode(): string`
Encode is a helper function for generating and appending a check character for the object's `$code` attribute.  When called, the `$code` attribute will be updated with its check character.  The result is equivalent to:
```php
$mod = new Mod3736();
$code = 'ABCDEFG';
$code .= $mod->generateCheckChar($code);
```

- `function generateCheckChar(?string $code): string`
Generates and returns a check character for the given code.  If a code is not passed as an argument, the function will instead use the object's `$code` attribute.
To create a class for a specific modulus, this function must be overridden with the algorithm for that modulus.

- `function validateCheckChar(): bool`
Takes the check character from the object's `$code` attribute and returns whether it matches the string's calculated check character.  

- `function setCode(string $code): void`
Setter for the `$code` attribute.  Strips non-alphanumeric characters automatically.

- `function getCode(): string`
Getter for the `$code` attribute.

- `function format(array $lengths, string $delimiter): string`
Returns the `$code` attribute delimited with sequences of the given lengths.  For example, if the current code is `'ABCDEFGR'` and you pass `[1, 3, 3, 1]` as the lengths array with `'-'` as the delimiter, the function will return `'A-BCD-EFG-R'`.

- `function getCheckChar(): string`
Helper function for returning the last character of the object's `$code` attribute.

### Mod3736

The `Mod3736` class extends `Mod` with functionality for Mod 37, 36.

- `function generateCheckChar(?string $code): string`
Generates a check character following Mod 37, 36

### GRid

The `GRid` class extends `Mod3736` and offers largely the same functionality, but also enforces the GRid standard and includes additional helpers for formatting.  If pssed a code in the constructor, the code will be checked for formatting as well as being parsed as in the `Mod3736` constructor.

#### Functions that are directly inherited from Mod3736:

`getCode()`

`getCheckChar()`

#### Functions that behave like Mod3736 but enforce GRid standards:

This includes not encoding already-encoded GRids and not validating unencoded GRids.

`encode()`

`generateCheckChar()`

`validateCheckChar()`

`setCode()`

#### GRid function reference

- `function format(): string`
Returns object's `$code` attribute delimited in the format XX-XXXXX-XXXXXXXXXX-X per GRid standard 2.1.  Function will throw GRidException if called while the `$code` attribute is unencoded.
As this function inherits from `Mod3736::format()`, it can be passed the `$lengths` and `$delimiter` arguments; however, these are ignored in favor of enforcing the GRid format.  No arguments need to be passed to this function.

- `static function checkGRid(string $code): bool`
Static helper function for quickly validating GRid codes without manually creating any instances of the GRid object, like so:
```php
// returns true
GRid::checkGRid('A1-2425G-ABC1234002-M');

// returns false
GRid::checkGRid('A1-2425G-ABC1234002-0');
```

## Addendum

[With respect to André Catita's implementation of ISO 7064 Mod 11, 2](http://andrecatita.com/code-snippets/iso-7064-mod-112-php/).

