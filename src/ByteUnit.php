<?php

namespace OpenSoutheners\ByteUnitConverter;

use OpenSoutheners\ByteUnitConverter\Attributes\AsByteUnit;

/**
 * This enum serves as a type safety for byte units based on the decimal system.
 *
 * @see https://en.wikipedia.org/wiki/Metric_prefix
 *
 * @author Rubén Robles <me@d8vjork.com>
 */
enum ByteUnit: string
{
    #[AsByteUnit(prefix: 'quetta', weight: 10, metric: MetricSystem::Decimal)]
    case QB = '1e30';

    #[AsByteUnit(prefix: 'quebi', weight: 10, metric: MetricSystem::Binary)]
    case QiB = '1.2676506e30';

    #[AsByteUnit(prefix: 'ronna', weight: 9, metric: MetricSystem::Decimal)]
    case RB = '1e27';

    #[AsByteUnit(prefix: 'robi', weight: 9, metric: MetricSystem::Binary)]
    case RiB = '1.23794004e27';

    #[AsByteUnit(prefix: 'yotta', weight: 8, metric: MetricSystem::Decimal)]
    case YB = '1e24';

    #[AsByteUnit(prefix: 'yobi', weight: 8, metric: MetricSystem::Binary)]
    case YiB = '1.20892582e24';

    #[AsByteUnit(prefix: 'zetta', weight: 7, metric: MetricSystem::Decimal)]
    case ZB = '1e21';

    #[AsByteUnit(prefix: 'zebi', weight: 7, metric: MetricSystem::Binary)]
    case ZiB = '1.18059162e21';

    #[AsByteUnit(prefix: 'exa', weight: 6, metric: MetricSystem::Decimal)]
    case EB = '1e18';

    #[AsByteUnit(prefix: 'exbi', weight: 6, metric: MetricSystem::Binary)]
    case EiB = '1.1529215e18';

    #[AsByteUnit(prefix: 'peta', weight: 5, metric: MetricSystem::Decimal)]
    case PB = '1e15';

    #[AsByteUnit(prefix: 'pebi', weight: 5, metric: MetricSystem::Binary)]
    case PiB = '1.12589991e15';

    #[AsByteUnit(prefix: 'tera', weight: 4, metric: MetricSystem::Decimal)]
    case TB = '1e12';

    #[AsByteUnit(prefix: 'tebi', weight: 4, metric: MetricSystem::Binary)]
    case TiB = '1.09951163e12';

    #[AsByteUnit(prefix: 'giga', weight: 3, metric: MetricSystem::Decimal)]
    case GB = '1e9';

    #[AsByteUnit(prefix: 'pebi', weight: 3, metric: MetricSystem::Binary)]
    case GiB = '1073741820';

    #[AsByteUnit(prefix: 'mega', weight: 2, metric: MetricSystem::Decimal)]
    case MB = '1e6';

    #[AsByteUnit(prefix: 'mebi', weight: 2, metric: MetricSystem::Binary)]
    case MiB = '1048576';

    #[AsByteUnit(prefix: 'kilo', weight: 1, metric: MetricSystem::Decimal)]
    case KB = '1e3';

    #[AsByteUnit(prefix: 'kibi', weight: 1, metric: MetricSystem::Binary)]
    case KiB = '1024';

    #[AsByteUnit(weight: 0)]
    case B = '1';

    /**
     * Check whether current unit instance is higher than specified unit.
     */
    public function higherThan(ByteUnit $unit): bool
    {
        return $this->value > $unit->value;
    }

    /**
     * Check whether current unit instance is lower than specified unit.
     */
    public function lowerThan(ByteUnit $unit): bool
    {
        return $this->value < $unit->value;
    }

    /**
     * Get place difference to unit.
     */
    public function toUnit(ByteUnit $unit): int
    {
        return $this->getWeight() - $unit->getWeight();
    }

    /**
     * Get metric system the byte unit is using.
     */
    public function getMetric(): MetricSystem
    {
        return $this->getAttribute()->metric;
    }

    /**
     * Get current byte unit case label.
     */
    public function getPrefix(): ?string
    {
        return $this->getAttribute()->prefix;
    }

    /**
     * Get current byte unit case weight.
     */
    public function getWeight(): int
    {
        return $this->getAttribute()->weight;
    }

    /**
     * Get current byte unit conversion as real number.
     */
    public function asNumber(): string
    {
        return number_format((float) $this->value, 0, '.', '');
    }

    /**
     * Get ByteUnit attribute from current backed case.
     */
    protected function getAttribute(): AsByteUnit
    {
        $reflection = new \ReflectionEnumBackedCase($this, $this->name);

        $reflectionAttributes = $reflection->getAttributes(AsByteUnit::class);

        /** @var \ReflectionAttribute<\OpenSoutheners\ByteUnitConverter\Attributes\AsByteUnit> $byteUnitAttribute */
        $byteUnitAttribute = reset($reflectionAttributes);

        return $byteUnitAttribute->newInstance();
    }
}
