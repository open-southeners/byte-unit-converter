# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2024-01-04

### Added

- Introducing operations with `add`, `sub` and `subtract` (**object bytes still immutable**, so new instance of the object will be returned on each operation)

### Changed

- **Breakchange**: Refactor `asRound` method now accepts integer and boolean (**default now is round up to 2 decimal positions if possible**).

## [2.2.0] - 2023-12-26

### Added

- `ByteUnitConverter::numberFormat()` static method to be reused within library (although can be used externally)

### Changed

- `ByteUnitConverter::new()` method now accepts strings or integers as input argument
- `ByteUnitConverter::from()` method now accepts strings, floats or integers as first input argument

### Fixed

- `ByteUnitConverter::asRound()` method now returns zero (0) results with decimals only if results are 0

## [2.1.0] - 2023-12-25

### Added

- `ByteUnitConverter::nearestUnit()` method to convert to nearest byte unit (and metric system):

```php
(string) ByteUnitConverter::new("1024")->nearestUnit(MetricSystem::Binary); // 1 KiB
(string) ByteUnitConverter::new("500")->nearestUnit(stoppingAt: ByteUnit::KB); // 0.50 KB
```

## [2.0.1] - 2023-12-25

### Fixed

- Conversion to bytes doesn't give decimals

## [2.0.0] - 2023-12-19

### Added

- `OpenSoutheners\ByteUnitConverter\MetricSystem` enum for decimal or binary metric systems
- `OpenSoutheners\ByteUnitConverter\DataUnit` enum for bytes or bits data units
- In order to support long numbers most-precisive operations now this library requires BCMath PHP extension
- `ByteUnitConverter::toArray` method to serialise to array
- `ByteUnitConverter::__toString` method to serialise to string with the closest unit appended
- `ByteUnitConverter::__serialize` & `ByteUnitConverter::__deserialize` methods to object serialization and deserialization
- `ByteUnit::lowerThan` method to check current unit lower than specified (opposite than already available `greaterThan`)
- `ByteUnitConverter::new` convenience method to create an instance of _ByteUnitConverter_ class
- `ByteUnitConverter::usingBits` & `ByteUnitConverter::usingBytes` to switch data units (**using bytes by default**)
- `ByteUnitConverter::asRound` while precision method only operates during decimal conversions, **this method will return a round number with no decimals** (defaulted to false). E.g:

```php
(string) ByteUnitConverter::new('1924')->toKiB(); // "1.87 KiB"
(string) ByteUnitConverter::new('1924')->asRound()->toKiB(); // "2 KiB"
```

### Fixed

- Support to long numbers (floats or integers on PHP must be strings)
- Working with conversions sometimes returns float numbers when integers were the only allowed
- Correctness in all conversions and comparisons between different systems (**decimal to binary**, **bytes to bits**...)

### Changed

- `OpenSoutheners\ByteUnitConverter\DecimalByteUnit` & `OpenSoutheners\ByteUnitConverter\BinaryByteUnit` replaced by `OpenSoutheners\ByteUnitConverter\ByteUnit`
- Precision argument to `conversion` and `toBytesFromUnit` methods 
- `to*` methods (`toTB`, `toGB`, `toMB`, etc...) now returns instance instead of number. Use the following to get it casted to a string:

```php
(string) ByteUnitConverter::new('1024')->toKiB(); // "1.00 KiB"
```

### Removed

- `ByteUnitConverter::conversion` static method (simplified to `ByteUnitConverter::new()...`)
- `ByteUnitConverter::toBitsFromUnit` static method (we only use bytes as a starter, can switch to bits while having an instance but not before)

## [1.0.0] - 2023-10-29

### Added

- Initial release!
