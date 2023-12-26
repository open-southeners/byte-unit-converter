---
description: >-
  Full documentation on how to use the ByteUnitConverter library on your PHP
  project.
---

# Usage

{% hint style="warning" %}
All methods use numeric strings as arguments and returns numeric strings as the results. All this is because native **PHP integers or floats doesn't support big numbers**. That's why **this library also requires BCMath extension**.
{% endhint %}

## ByteUnit

This enum works as a type safety for conversions using `ByteUnitConverter` utility class, check its documentation to see its usage.

## DataUnit

Same as [ByteUnit](usage.md#byteunit), this is also an enum used for perform conversion between different data units (bytes or bits).

{% hint style="info" %}
Check this [Wikipedia article](https://en.wikipedia.org/wiki/Byte) to understand what a byte is in terms of bytes and more.
{% endhint %}

## MetricSystem

This is another enum used to convert byte units between different metric systems.

{% hint style="info" %}
Check this [Wikipedia article](https://en.wikipedia.org/wiki/Metric\_prefix) to understand what a metric system means as of a general perspective.
{% endhint %}

## ByteUnitConverter

This utility class is used to convert between:

* **Different byte units** (KB to MB, TB to GB, etc...)
* **Different data units** (kilobytes to kilobits, bytes to bits, etc...)
* And **different metric systems** (kilobytes to kibibytes, tebibytes to gigabytes, etc...).

### new

Create new instance from bytes:

```php
(string) ByteUnitConverter::new(1000)->toKB(); // '1 KB'
```

### toBytesFromUnit

Get bytes from unit:

```php
ByteUnitConverter::toBytesFromUnit('1', ByteUnit::KiB); // '1024'
```

### from

Get new instance from value and unit:

```php
(string) ByteUnitConverter::from(1, ByteUnit::MB)->toKB(); // '1000 KB'
```

### numberFormat

{% hint style="info" %}
Reused internally within the library but publicly available.
{% endhint %}

Format numbers using PHP's [`number_format`](https://www.php.net/manual/en/function.number-format.php) built-in function but removing thousands separator:

```php
ByteUnitConverter::numberFormat('1000.00', 0); // '1000'
```

### setPrecision

{% hint style="info" %}
Default to 2 as is same as the decimal positions from the output.
{% endhint %}

{% hint style="warning" %}
**See asRound to remove decimals instead of setting this option to 0**.
{% endhint %}

Customise precision for some conversion operations like divisions:

```php
(string) ByteUnitConverter::new('500')->toKiB(); // '0.48 KiB'
(string) ByteUnitConverter::new('500')->setPrecision(3)->toKiB(); // '0.488 KiB'
(string) ByteUnitConverter::new('500')->setPrecision(6)->toKiB(); // '0.488281 KiB'
```

### asRound

Round result to a integer without decimals:

```php
(string) ByteUnitConverter::new('500')->asRound()->toKiB(); // '0.5 KiB'
```

### useUnitLabel

Round result to a integer without decimals:

```php
(string) ByteUnitConverter::new('500')->useUnitLabel()->toKiB(); // '0.5 kibibyte'
(string) ByteUnitConverter::new('2000')->useUnitLabel()->toKB(); // '2 kilobytes'
```

### nearestUnit

Convert bytes to their nearest unit on the specified metric system:

```php
(string) ByteUnitConverter::new('10000239595')->nearestUnit(); // '10 GB'
(string) ByteUnitConverter::new('102239595')->nearestUnit(); // '102.23 MB'

// Can also specify a different metric system
(string) ByteUnitConverter::new('10000239595')->nearestUnit(MetricSystem::Binary); // '9.31 GiB'
(string) ByteUnitConverter::new('102239595')->nearestUnit(MetricSystem::Binary); // '97.50 MiB'
```

### getValue

Get the resulting numeric value from the conversion:

```php
ByteUnitConverter::new('500')->toKB()->getValue(); // '0.50'
```

### getUnit

Get the resulting byte unit as string from the conversion:

```php
ByteUnitConverter::new('1000')->toKB()->getUnit(); // 'KB'
ByteUnitConverter::new('1000')->toKB()->useUnitLabel()->getUnit(); // 'kilobyte'
ByteUnitConverter::new('2000')->toKB()->useUnitLabel()->getUnit(); // 'kilobytes'
```

### to

Convert bytes to byte unit:

```php
(string) ByteUnitConverter::new('500')->to(ByteUnit::KB); // '0.5 KB'

// or use convenience method
(string) ByteUnitConverter::new('500')->toKB(); // '0.5 KB'
```

{% hint style="info" %}
All byte units from all metric systems available in this library have their own conversion methods for convenience.
{% endhint %}

### usingBytes

Perform conversions using bytes data unit:

```php
(string) ByteUnitConverter::new('500')->usingBytes()->toKB(); // '0.50 KB'
```

{% hint style="info" %}
This library already use bytes as a default data unit.
{% endhint %}

### usingBits

Perform conversions using bits data unit:

```php
(string) ByteUnitConverter::new('500')
    ->usingBits()
    ->useUnitLabel()
    ->toKB(); // '4 kilobits'
```

### \_\_toString

Serialise conversion result to string:

```php
ByteUnitConverter::new('500')->__toString(); // '500 B'

// or just casting as we always use
(string) ByteUnitConverter::new('500'); // '500 B'
```

### toArray

Serialise conversion result to multidimensional array:

```php
ByteUnitConverter::new('1000')->toKB()->toArray(); // ['unit' => 'KB', 'unit_label' => 'kilobyte', 'value' => '1.00']
```

### serialize

The `ByteUnitConverter` utility class can be also serialised/deserialised using PHP serialisation:

```php
$serialised = serialize(ByteUnitConverter::new('1000')->toKB()); // 'O:50:"OpenSoutheners\ByteUnitConverter\ByteUnitConverter":3:{s:5:"bytes";s:4:"1000";s:9:"byte_unit";s:3:"1e3";s:9:"data_unit";s:1:"1";}'

unserialize($serialised); // ByteUnitConverter instance
```
