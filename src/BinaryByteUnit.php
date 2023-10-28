<?php

namespace OpenSoutheners\ByteUnitConverter;

/**
 * This enum serves as a type safety for byte units based on the binary system.
 *
 * @see https://en.wikipedia.org/wiki/Binary_prefix
 *
 * @author Rubén Robles <me@d8vjork.com>
 */
enum BinaryByteUnit: int implements ByteUnit
{
    use ComparesByteUnits;

    /**
     * Quebibyte
     */
    case QiB = 10;

    /**
     * Robibyte
     */
    case RiB = 9;

    /**
     * Yobibyte
     */
    case YiB = 8;

    /**
     * Zebibyte
     */
    case ZiB = 7;

    /**
     * Exbibyte
     */
    case EiB = 6;

    /**
     * Pebibyte
     */
    case PiB = 5;

    /**
     * Tebibyte
     */
    case TiB = 4;

    /**
     * Gibibyte
     */
    case GiB = 3;

    /**
     * Mebibyte
     */
    case MiB = 2;

    /**
     * Kibibyte
     */
    case KiB = 1;

    /**
     * Byte
     */
    case B = 0;
}
