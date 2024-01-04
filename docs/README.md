---
description: Installation and usage of the Byte unit converter library.
---

# Getting started

Install the library using [Composer](https://getcomposer.org/):

```sh
composer require open-southeners/byte-unit-converter
```

### Basic usage

To use the library simply import the `OpenSoutheners\ByteUnitConverter\ByteUnitConverter` class and use it:

```php
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;
use OpenSoutheners\ByteUnitConverter\ByteUnit;

// Use preferably the static method new to construct an instance
(string) ByteUnitConverter::new(1000)->toKB(); // "1.00 KB"
(string) ByteUnitConverter::new(1024)->toKiB(); // "1.00 KiB"

// Or starting from a specific unit (if known)
(string) ByteUnitConverter::new(1000, ByteUnit::MB)->toGB(); // "1.00 GB"

// Converting to the nearest byte unit based on the bytes
(string) ByteUnitConverter::new('102239595')->nearestUnit(); // '102.23 MB'

// Or even basic manipulations like add or subtract
(string) ByteUnitConverter::new(1000, ByteUnit::MB)->subtract(500, ByteUnit::MB)->toMB(); // "500 MB"

(string) ByteUnitConverter::new(1000, ByteUnit::MB)->add(500, ByteUnit::MB)->toGB(); // "1.5 GB"
```
