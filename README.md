#Soundways\ISO7064

This is an implementation of ISO 7064:1983 Mod 37, 36, intended primarily for use in calculating the check character for [GRid standard 2.1](https://ifpi.org/downloads/GRid_Standard_v2_1.pdf).

This package is only available for PHP 7.

##Installation

TODO: add composer installation instructions

###Dependencies

`ext-mbstring` is required for `mb_strlen`.  If `ext-mbstring` is unavailable for any reason, `symfony\polyfill-mbstring` is required in its place.

##Quickstart

TODO

##Reference

The namespace contains the `Mod3736` class for general-purpose encoding within the ISO standard and the `GRid` class which enforces GRid specific requirements and includes some helper functions.

##Addendum

[With respect to Andre Catita's implementation of Iso7064mod112](http://andrecatita.com/code-snippets/iso-7064-mod-112-php/).

