<?php

namespace OpenSoutheners\ByteUnitConverter;

/**
 * Bytes unit converter is a very small PHP 8.1+ functional utility to convert byte based decimals into different units.
 *
 * Something like ByteCountFormatter from Swift but less end-user focused.
 *
 * Logically it always work around converting everything to bytes.
 *
 * @author Rubén Robles <me@d8vjork.com>
 */
class ByteUnitConverter
{
    final public function __construct(private readonly int $bytes)
    {
        //
    }

    /**
     * Start converting from specified byte count & unit.
     */
    public static function from(int|float $value, ByteUnit $unit): static
    {
        return new static(static::toBytesFromUnit($value, $unit));
    }

    /**
     * Do conversion between two byte units.
     */
    public static function conversion(int|float $value, ByteUnit $fromUnit, ByteUnit $toUnit): int|float
    {
        return (new static(static::toBytesFromUnit($value, $fromUnit)))->to($toUnit);
    }

    /**
     * Converts from specified unit to bytes.
     *
     * @throws \Exception
     */
    public static function toBytesFromUnit(int|float $value, ByteUnit $unit): int
    {
        if (is_float($value) && $unit->value === 0) {
            throw new \Exception('Bytes cannot be a float unit.');
        }

        // saves the noop on bytes from any metric system
        if ($unit->value === 0) {
            return (int) $value;
        }

        return (int) $value * pow(static::baseFromUnit($unit), $unit->value);
    }

    /**
     * Converts from specified unit to bits.
     */
    public static function toBitsFromUnit(int|float $value, ByteUnit $unit): int
    {
        return self::toBytesFromUnit($value, $unit) * 8;
    }

    /**
     * Get numeric base used for make conversion between byte units.
     */
    public static function baseFromUnit(ByteUnit $unit): int
    {
        return match (get_class($unit)) {
            DecimalByteUnit::class => 1000,
            BinaryByteUnit::class => 1024,
            default => throw new \Exception('Byte unit class not supported by ByteUnitConverted.'),
        };
    }

    /**
     * Convert unit to desired unit.
     */
    public function to(ByteUnit $unit): int|float
    {
        return $this->bytes / pow(static::baseFromUnit($unit), $unit->value);
    }

    /**
     * Convert unit to quettabyte.
     */
    public function toQB(): int|float
    {
        return $this->to(DecimalByteUnit::QB);
    }

    /**
     * Convert unit to quettabyte.
     *
     * @see toQB()
     *
     * @codeCoverageIgnore
     */
    public function toQuettabyte(): int|float
    {
        return $this->toQB();
    }

    /**
     * Convert unit to quebibyte.
     */
    public function toQiB(): int|float
    {
        return $this->to(BinaryByteUnit::QiB);
    }

    /**
     * Convert unit to quebibyte.
     *
     * @see toQiB()
     *
     * @codeCoverageIgnore
     */
    public function toQuebibyte(): int|float
    {
        return $this->toQiB();
    }

    /**
     * Convert unit to ronnabyte.
     */
    public function toRB(): int|float
    {
        return $this->to(DecimalByteUnit::RB);
    }

    /**
     * Convert unit to ronnabyte.
     *
     * @see toRB()
     *
     * @codeCoverageIgnore
     */
    public function toRonnabyte(): int|float
    {
        return $this->toRB();
    }

    /**
     * Convert unit to robibyte.
     */
    public function toRiB(): int|float
    {
        return $this->to(BinaryByteUnit::RiB);
    }

    /**
     * Convert unit to robibyte.
     *
     * @see toRiB()
     *
     * @codeCoverageIgnore
     */
    public function toRobibyte(): int|float
    {
        return $this->toRiB();
    }

    /**
     * Convert unit to yettabyte.
     */
    public function toYB(): int|float
    {
        return $this->to(DecimalByteUnit::YB);
    }

    /**
     * Convert unit to yettabyte.
     *
     * @see toYB()
     *
     * @codeCoverageIgnore
     */
    public function toYettabyte(): int|float
    {
        return $this->toYB();
    }

    /**
     * Convert unit to yebibyte.
     */
    public function toYiB(): int|float
    {
        return $this->to(BinaryByteUnit::YiB);
    }

    /**
     * Convert unit to yebibyte.
     *
     * @see toYiB()
     *
     * @codeCoverageIgnore
     */
    public function toYebibyte(): int|float
    {
        return $this->toYiB();
    }

    /**
     * Convert unit to zettabyte.
     */
    public function toZB(): int|float
    {
        return $this->to(DecimalByteUnit::ZB);
    }

    /**
     * Convert unit to zettabyte.
     *
     * @see toZB()
     *
     * @codeCoverageIgnore
     */
    public function toZettabyte(): int|float
    {
        return $this->toZB();
    }

    /**
     * Convert unit to zebibyte.
     */
    public function toZiB(): int|float
    {
        return $this->to(BinaryByteUnit::ZiB);
    }

    /**
     * Convert unit to zebibyte.
     *
     * @see toZiB()
     *
     * @codeCoverageIgnore
     */
    public function toZebibyte(): int|float
    {
        return $this->toZiB();
    }

    /**
     * Convert unit to exabyte.
     */
    public function toEB(): int|float
    {
        return $this->to(DecimalByteUnit::EB);
    }

    /**
     * Convert unit to exabyte.
     *
     * @see toEB()
     *
     * @codeCoverageIgnore
     */
    public function toExabyte(): int|float
    {
        return $this->toEB();
    }

    /**
     * Convert unit to exbibyte.
     */
    public function toEiB(): int|float
    {
        return $this->to(BinaryByteUnit::EiB);
    }

    /**
     * Convert unit to exbibyte.
     *
     * @see toEiB()
     *
     * @codeCoverageIgnore
     */
    public function toExbibyte(): int|float
    {
        return $this->toEiB();
    }

    /**
     * Convert unit to petabyte.
     */
    public function toPB(): int|float
    {
        return $this->to(DecimalByteUnit::PB);
    }

    /**
     * Convert unit to petabyte.
     *
     * @see toPB()
     *
     * @codeCoverageIgnore
     */
    public function toPetabyte(): int|float
    {
        return $this->toPB();
    }

    /**
     * Convert unit to petabyte.
     */
    public function toPiB(): int|float
    {
        return $this->to(BinaryByteUnit::PiB);
    }

    /**
     * Convert unit to pebibyte.
     *
     * @see toPiB()
     *
     * @codeCoverageIgnore
     */
    public function toPebibyte(): int|float
    {
        return $this->toPiB();
    }

    /**
     * Convert unit to terabyte.
     */
    public function toTB(): int|float
    {
        return $this->to(DecimalByteUnit::TB);
    }

    /**
     * Convert unit to terabyte.
     *
     * @see toTB()
     *
     * @codeCoverageIgnore
     */
    public function toTerabyte(): int|float
    {
        return $this->toTB();
    }

    /**
     * Convert unit to tebibyte.
     */
    public function toTiB(): int|float
    {
        return $this->to(BinaryByteUnit::TiB);
    }

    /**
     * Convert unit to tebibyte.
     *
     * @see toTiB()
     *
     * @codeCoverageIgnore
     */
    public function toTebibyte(): int|float
    {
        return $this->toTiB();
    }

    /**
     * Convert unit to gigabyte.
     */
    public function toGB(): int|float
    {
        return $this->to(DecimalByteUnit::GB);
    }

    /**
     * Convert unit to gigabyte.
     *
     * @see toGB()
     *
     * @codeCoverageIgnore
     */
    public function toGigabyte(): int|float
    {
        return $this->toGB();
    }

    /**
     * Convert unit to gibibyte.
     */
    public function toGiB(): int|float
    {
        return $this->to(BinaryByteUnit::GiB);
    }

    /**
     * Convert unit to gibibyte.
     *
     * @see toGiB()
     *
     * @codeCoverageIgnore
     */
    public function toGibibyte(): int|float
    {
        return $this->toGiB();
    }

    /**
     * Convert unit to megabyte.
     */
    public function toMB(): int|float
    {
        return $this->to(DecimalByteUnit::MB);
    }

    /**
     * Convert unit to megabyte.
     *
     * @see toMB()
     *
     * @codeCoverageIgnore
     */
    public function toMegabyte(): int|float
    {
        return $this->toMB();
    }

    /**
     * Convert unit to mebibyte.
     */
    public function toMiB(): int|float
    {
        return $this->to(BinaryByteUnit::MiB);
    }

    /**
     * Convert unit to mebibyte.
     *
     * @see toMiB()
     *
     * @codeCoverageIgnore
     */
    public function toMebibyte(): int|float
    {
        return $this->toMiB();
    }

    /**
     * Convert unit to kilobyte.
     */
    public function toKB(): int|float
    {
        return $this->to(DecimalByteUnit::KB);
    }

    /**
     * Convert unit to kilobyte.
     *
     * @see toKB()
     *
     * @codeCoverageIgnore
     */
    public function toKilobyte(): int|float
    {
        return $this->toKB();
    }

    /**
     * Convert unit to kibibyte.
     */
    public function toKiB(): int|float
    {
        return $this->to(BinaryByteUnit::KiB);
    }

    /**
     * Convert unit to kibibyte.
     *
     * @see toKiB()
     *
     * @codeCoverageIgnore
     */
    public function toKibibyte(): int|float
    {
        return $this->toKiB();
    }
}
