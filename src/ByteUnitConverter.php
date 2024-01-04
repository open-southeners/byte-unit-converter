<?php

declare(strict_types=1);

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
final class ByteUnitConverter
{
    private ByteUnit $unit = ByteUnit::B;

    private DataUnit $dataUnit = DataUnit::B;

    private int $precision = 4;

    private int|bool $roundAsMuchAs = 2;

    private bool $unitLabelAsOutput = false;

    public function __construct(private readonly string $bytes)
    {
        if (! is_numeric($bytes)) {
            throw new \Exception('Not numeric value as bytes not supported.');
        }
    }

    /**
     * Create new instance.
     */
    public static function new(int|string $bytes): static
    {
        return new self((string) $bytes);
    }

    /**
     * Create new instance converting from value and unit to bytes for conversions.
     */
    public static function from(int|float|string $value, ByteUnit $unit, int $precision = 2): static
    {
        return new self(static::toBytesFromUnit((string) $value, $unit, $precision));
    }

    /**
     * Converts from specified unit to bytes.
     *
     * @throws \Exception
     */
    public static function toBytesFromUnit(string $value, ByteUnit $unit, int $precision = 2): string
    {
        // saves the noop on bytes from any metric system
        if ($unit->value === '1') {
            return $value;
        }

        return bcmul($value, $unit->asNumber(), $precision);
    }

    /**
     * Like PHP's number_format function but without thousands separator.
     */
    public static function numberFormat(string $number, int $decimals = 0): string
    {
        $formattedNumber = number_format(
            num: (float) $number,
            decimals: $decimals,
            thousands_separator: ''
        );

        if (! str_contains($formattedNumber, '.')) {
            return $formattedNumber;
        }

        return rtrim(rtrim($formattedNumber, '0'), '.');
    }

    /**
     * Format numeric string using round by calculated user-input decimals if specified.
     */
    private function roundNumericString(string $number): string
    {
        [$whole, $decimal] = explode('.', $number) + ['0', ''];

        $numberDecimalPositions = strlen($decimal);

        if (! $this->roundAsMuchAs) {
            return static::numberFormat($number, $numberDecimalPositions);
        }

        $decimalPositions = $this->roundAsMuchAs === true
            ? $numberDecimalPositions
            : $this->roundAsMuchAs;

        if ($numberDecimalPositions <= $decimalPositions) {
            return static::numberFormat($number, strspn($decimal, '0') ?: 1);
        }

        $firstRound = static::numberFormat($number, $numberDecimalPositions);

        [$firstRoundWhole, $firstRoundDecimal] = explode('.', $firstRound) + ['0', ''];

        $numberDecimalPositions = strlen($firstRoundDecimal);

        if ($numberDecimalPositions <= $decimalPositions) {
            return $firstRound;
        }

        $secondRound = static::numberFormat($firstRound, --$numberDecimalPositions);

        return $secondRound > 0 ? $secondRound : $firstRound;
    }

    /**
     * Set decimal precision for all conversions.
     */
    public function setPrecision(int $precision): self
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * Round resulting bytes number to have as near as the specified decimal digits.
     *
     * For e.g. asRound(true) = 1, asRound(false) = -1 (all decimals)
     */
    public function asRound(int|bool $value = true): self
    {
        $this->roundAsMuchAs = $value;

        return $this;
    }

    /**
     * Use byte unit full name instead.
     *
     * For e.g. "1 GiB" to "1 gibibyte".
     */
    public function useUnitLabel(bool $value = true): self
    {
        $this->unitLabelAsOutput = $value;

        return $this;
    }

    /**
     * Assign nearest bytes unit by metric system from current bytes value.
     */
    public function nearestUnit(?MetricSystem $metric = MetricSystem::Decimal, ?ByteUnit $stoppingAt = null): self
    {
        $nearestUnit = null;
        $byteUnits = ByteUnit::cases();
        $i = 0;

        while (! $nearestUnit && $i < count($byteUnits)) {
            $unit = $byteUnits[$i];
            $unitMetricSystem = $unit->getMetric();

            $i++;

            if (! $unitMetricSystem || $unitMetricSystem !== $metric) {
                continue;
            }

            if ($unit === $stoppingAt || bcsub($this->bytes, $unit->asNumber(), 0) >= 0) {
                $nearestUnit = $unit;
            }
        }

        $this->unit = $nearestUnit ?? ByteUnit::B;

        return $this;
    }

    /**
     * Add byte unit quantity to the current returning new instance.
     */
    public function add(int|float|string $quantity, ByteUnit $unit): self
    {
        return new self(
            bcadd($this->bytes, static::toBytesFromUnit((string) $quantity, $unit), $this->precision)
        );
    }

    /**
     * Subtract byte unit quantity to the current returning new instance.
     */
    public function subtract(int|float|string $quantity, ByteUnit $unit): self
    {
        return new self(
            bcsub($this->bytes, static::toBytesFromUnit((string) $quantity, $unit), $this->precision)
        );
    }

    /**
     * Subtract byte unit quantity to the current returning new instance.
     *
     * @codeCoverageIgnore
     */
    public function sub(int|float|string $quantity, ByteUnit $unit): self
    {
        return $this->subtract($quantity, $unit);
    }

    /**
     * Get conversion result numeric value.
     */
    public function getValue(): string
    {
        $value = bcmul($this->dataUnit->value, $this->bytes, $this->precision);

        $value = bcdiv($value, $this->unit->asNumber(), $this->precision);

        return $this->roundNumericString($value);
    }

    /**
     * Get conversion result unit.
     */
    public function getUnit(bool $plural = false, bool $forceLabel = false): string
    {
        if (! $forceLabel && ! $this->unitLabelAsOutput) {
            return $this->dataUnit === DataUnit::b && $this->unit === ByteUnit::B
                ? DataUnit::b->name
                : $this->unit->name;
        }

        $baseUnit = $this->unit->getPrefix().$this->dataUnit->getLabel();

        if ($plural) {
            $baseUnit .= 's';
        }

        return $baseUnit;
    }

    /**
     * Convert unit to desired unit.
     */
    public function to(ByteUnit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Convert unit to quettabyte.
     */
    public function toQB(): self
    {
        return $this->to(ByteUnit::QB);
    }

    /**
     * Convert unit to quettabyte.
     *
     * @see toQB()
     *
     * @codeCoverageIgnore
     */
    public function toQuettabyte(): self
    {
        return $this->toQB();
    }

    /**
     * Convert unit to quebibyte.
     */
    public function toQiB(): self
    {
        return $this->to(ByteUnit::QiB);
    }

    /**
     * Convert unit to quebibyte.
     *
     * @see toQiB()
     *
     * @codeCoverageIgnore
     */
    public function toQuebibyte(): self
    {
        return $this->toQiB();
    }

    /**
     * Convert unit to ronnabyte.
     */
    public function toRB(): self
    {
        return $this->to(ByteUnit::RB);
    }

    /**
     * Convert unit to ronnabyte.
     *
     * @see toRB()
     *
     * @codeCoverageIgnore
     */
    public function toRonnabyte(): self
    {
        return $this->toRB();
    }

    /**
     * Convert unit to robibyte.
     */
    public function toRiB(): self
    {
        return $this->to(ByteUnit::RiB);
    }

    /**
     * Convert unit to robibyte.
     *
     * @see toRiB()
     *
     * @codeCoverageIgnore
     */
    public function toRobibyte(): self
    {
        return $this->toRiB();
    }

    /**
     * Convert unit to yettabyte.
     */
    public function toYB(): self
    {
        return $this->to(ByteUnit::YB);
    }

    /**
     * Convert unit to yettabyte.
     *
     * @see toYB()
     *
     * @codeCoverageIgnore
     */
    public function toYettabyte(): self
    {
        return $this->toYB();
    }

    /**
     * Convert unit to yebibyte.
     */
    public function toYiB(): self
    {
        return $this->to(ByteUnit::YiB);
    }

    /**
     * Convert unit to yebibyte.
     *
     * @see toYiB()
     *
     * @codeCoverageIgnore
     */
    public function toYebibyte(): self
    {
        return $this->toYiB();
    }

    /**
     * Convert unit to zettabyte.
     */
    public function toZB(): self
    {
        return $this->to(ByteUnit::ZB);
    }

    /**
     * Convert unit to zettabyte.
     *
     * @see toZB()
     *
     * @codeCoverageIgnore
     */
    public function toZettabyte(): self
    {
        return $this->toZB();
    }

    /**
     * Convert unit to zebibyte.
     */
    public function toZiB(): self
    {
        return $this->to(ByteUnit::ZiB);
    }

    /**
     * Convert unit to zebibyte.
     *
     * @see toZiB()
     *
     * @codeCoverageIgnore
     */
    public function toZebibyte(): self
    {
        return $this->toZiB();
    }

    /**
     * Convert unit to exabyte.
     */
    public function toEB(): self
    {
        return $this->to(ByteUnit::EB);
    }

    /**
     * Convert unit to exabyte.
     *
     * @see toEB()
     *
     * @codeCoverageIgnore
     */
    public function toExabyte(): self
    {
        return $this->toEB();
    }

    /**
     * Convert unit to exbibyte.
     */
    public function toEiB(): self
    {
        return $this->to(ByteUnit::EiB);
    }

    /**
     * Convert unit to exbibyte.
     *
     * @see toEiB()
     *
     * @codeCoverageIgnore
     */
    public function toExbibyte(): self
    {
        return $this->toEiB();
    }

    /**
     * Convert unit to petabyte.
     */
    public function toPB(): self
    {
        return $this->to(ByteUnit::PB);
    }

    /**
     * Convert unit to petabyte.
     *
     * @see toPB()
     *
     * @codeCoverageIgnore
     */
    public function toPetabyte(): self
    {
        return $this->toPB();
    }

    /**
     * Convert unit to petabyte.
     */
    public function toPiB(): self
    {
        return $this->to(ByteUnit::PiB);
    }

    /**
     * Convert unit to pebibyte.
     *
     * @see toPiB()
     *
     * @codeCoverageIgnore
     */
    public function toPebibyte(): self
    {
        return $this->toPiB();
    }

    /**
     * Convert unit to terabyte.
     */
    public function toTB(): self
    {
        return $this->to(ByteUnit::TB);
    }

    /**
     * Convert unit to terabyte.
     *
     * @see toTB()
     *
     * @codeCoverageIgnore
     */
    public function toTerabyte(): self
    {
        return $this->toTB();
    }

    /**
     * Convert unit to tebibyte.
     */
    public function toTiB(): self
    {
        return $this->to(ByteUnit::TiB);
    }

    /**
     * Convert unit to tebibyte.
     *
     * @see toTiB()
     *
     * @codeCoverageIgnore
     */
    public function toTebibyte(): self
    {
        return $this->toTiB();
    }

    /**
     * Convert unit to gigabyte.
     */
    public function toGB(): self
    {
        return $this->to(ByteUnit::GB);
    }

    /**
     * Convert unit to gigabyte.
     *
     * @see toGB()
     *
     * @codeCoverageIgnore
     */
    public function toGigabyte(): self
    {
        return $this->toGB();
    }

    /**
     * Convert unit to gibibyte.
     */
    public function toGiB(): self
    {
        return $this->to(ByteUnit::GiB);
    }

    /**
     * Convert unit to gibibyte.
     *
     * @see toGiB()
     *
     * @codeCoverageIgnore
     */
    public function toGibibyte(): self
    {
        return $this->toGiB();
    }

    /**
     * Convert unit to megabyte.
     */
    public function toMB(): self
    {
        return $this->to(ByteUnit::MB);
    }

    /**
     * Convert unit to megabyte.
     *
     * @see toMB()
     *
     * @codeCoverageIgnore
     */
    public function toMegabyte(): self
    {
        return $this->toMB();
    }

    /**
     * Convert unit to mebibyte.
     */
    public function toMiB(): self
    {
        return $this->to(ByteUnit::MiB);
    }

    /**
     * Convert unit to mebibyte.
     *
     * @see toMiB()
     *
     * @codeCoverageIgnore
     */
    public function toMebibyte(): self
    {
        return $this->toMiB();
    }

    /**
     * Convert unit to kilobyte.
     */
    public function toKB(): self
    {
        return $this->to(ByteUnit::KB);
    }

    /**
     * Convert unit to kilobyte.
     *
     * @see toKB()
     *
     * @codeCoverageIgnore
     */
    public function toKilobyte(): self
    {
        return $this->toKB();
    }

    /**
     * Convert unit to kibibyte.
     */
    public function toKiB(): self
    {
        return $this->to(ByteUnit::KiB);
    }

    /**
     * Convert unit to kibibyte.
     *
     * @see toKiB()
     *
     * @codeCoverageIgnore
     */
    public function toKibibyte(): self
    {
        return $this->toKiB();
    }

    /**
     * Convert unit to bytes.
     */
    public function usingBytes(): self
    {
        $this->dataUnit = DataUnit::B;

        return $this->asRound();
    }

    /**
     * Convert unit to bits.
     */
    public function usingBits(): self
    {
        $this->dataUnit = DataUnit::b;

        return $this->asRound();
    }

    /**
     * Serialise result to string with unit name appended.
     */
    public function __toString(): string
    {
        $value = $this->getValue();

        return "{$value} ".$this->getUnit($value > 1);
    }

    /**
     * Serialise result to array.
     */
    public function toArray(): array
    {
        $value = $this->getValue();

        return [
            'unit' => $this->unit->name,
            'unit_label' => $this->getUnit($value > 0, true),
            'value' => $value,
        ];
    }

    /**
     * Serialise object instance into string.
     */
    public function __serialize(): array
    {
        return [
            'bytes' => $this->bytes,
            'byte_unit' => $this->unit->value,
            'data_unit' => $this->dataUnit->value,
        ];
    }

    /**
     * Deserialize string into object instance.
     */
    public function __unserialize(array $data): void
    {
        $this->bytes = $data['bytes'];
        $this->unit = ByteUnit::from($data['byte_unit']);
        $this->dataUnit = DataUnit::from($data['data_unit']);
    }
}
