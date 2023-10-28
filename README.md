Byte unit converter [![required php version](https://img.shields.io/packagist/php-v/open-southeners/byte-unit-converter)](https://www.php.net/supported-versions.php) [![codecov](https://codecov.io/gh/open-southeners/byte-unit-converter/branch/main/graph/badge.svg?token=qcEglkQDg4)](https://codecov.io/gh/open-southeners/byte-unit-converter) [![Edit on VSCode online](https://img.shields.io/badge/vscode-edit%20online-blue?logo=visualstudiocode)](https://vscode.dev/github/open-southeners/byte-unit-converter)
===

A utility written in PHP8.1+ to convert multiple-byte units with no dependencies.

## Getting started

```
composer require open-southeners/byte-unit-converter
```

### Usage

**Before start using this package first familiarice with the concept of [multi-byte unit](https://en.wikipedia.org/wiki/Byte#Multiple-byte_units)**.

Example usage:

```php
<?php

use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\DecimalByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;

// Using decimal system
ByteUnitConverter::from(1, DecimalByteUnit::TB)->to(DecimalByteUnit::GB); // 1000
ByteUnitConverter::from(1, DecimalByteUnit::TB)->toGB(); // 1000
ByteUnitConverter::from(1, DecimalByteUnit::TB)->toGigabyte(); // 1000
ByteUnitConverter::conversion(1, DecimalByteUnit::TB, DecimalByteUnit::GB); // 1000

// Using binary system
ByteUnitConverter::from(1, BinaryByteUnit::TiB)->to(BinaryByteUnit::GiB); // 1024
ByteUnitConverter::from(1, BinaryByteUnit::TiB)->toGiB(); // 1024
ByteUnitConverter::from(1, BinaryByteUnit::TiB)->toGibibyte(); // 1024
ByteUnitConverter::conversion(1, BinaryByteUnit::TiB, BinaryByteUnit::GiB); // 1024
```

### Considerations

**We don't use round** or any method to remove extra decimals, **we consider this should be done by the developer using this library** so take this in mind.

Also take in mind the different units within the enums, each one is using different conversion base (check usage).

## Motivation

After learning the Swift programming language I found a very useful utility under darwin systems (Mac OS X) which is the [ByteCountFormatter](https://developer.apple.com/documentation/foundation/bytecountformatter) there is where I based this project, also needed this for a long-run project (also used in our other package: [machine-stats](https://github.com/open-southeners/machine-stats)) therefore here it is.

## Partners

[![skore logo](https://github.com/open-southeners/partners/raw/main/logos/skore_logo.png)](https://getskore.com)

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
