<?php

namespace OpenSoutheners\ByteUnitConverter;

/**
 * This enum serves as a type safety for byte units based on the decimal system.
 *
 * @see https://en.wikipedia.org/wiki/Metric_prefix
 *
 * @author Rubén Robles <me@d8vjork.com>
 */
enum DecimalByteUnit: int implements ByteUnit
{
    use ComparesByteUnits;

    /**
     * Quettabyte
     */
    case QB = 10;

    /**
     * Ronnabyte
     */
    case RB = 9;

    /**
     * Yottabyte
     */
    case YB = 8;

    /**
     * Zettabyte
     */
    case ZB = 7;

    /**
     * Exabyte
     */
    case EB = 6;

    /**
     * Petabyte
     */
    case PB = 5;

    /**
     * Terabyte
     */
    case TB = 4;

    /**
     * Gigabyte
     */
    case GB = 3;

    /**
     * Megabyte
     */
    case MB = 2;

    /**
     * Kilobyte
     */
    case KB = 1;

    /**
     * Byte
     */
    case B = 0;
}
