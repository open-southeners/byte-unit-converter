<?php

namespace OpenSoutheners\ByteUnitConverter\Tests;

use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;
use OpenSoutheners\ByteUnitConverter\DecimalByteUnit;
use PHPUnit\Framework\TestCase;

enum MyOwnByteUnit: int implements ByteUnit
{
    case GB = 1;
}

class ByteUnitConverterTest extends TestCase
{
    public function testGetBaseFromBinaryByteUnitEqualsBase2()
    {
        $this->assertEquals(1024, ByteUnitConverter::baseFromUnit(BinaryByteUnit::GiB));
    }

    public function testGetBaseFromDecimalByteUnitEqualsBase10()
    {
        $this->assertEquals(1000, ByteUnitConverter::baseFromUnit(DecimalByteUnit::GB));
    }

    public function testGetBaseFromUnknownClassUsingInterfaceWillThrowException()
    {
        $this->expectExceptionObject(new \Exception('Byte unit class not supported by ByteUnitConverted.'));

        ByteUnitConverter::baseFromUnit(MyOwnByteUnit::GB);
    }

    public function testFromMethodReturnsSelfInstance()
    {
        $this->assertInstanceOf(ByteUnitConverter::class, ByteUnitConverter::from(1, BinaryByteUnit::GiB));
    }

    public function testConversionBetweenBinaryByteUnits()
    {
        $this->assertEquals(1024, ByteUnitConverter::conversion(1, BinaryByteUnit::MiB, BinaryByteUnit::KiB));
        $this->assertEquals(1, ByteUnitConverter::conversion(1024, BinaryByteUnit::KiB, BinaryByteUnit::MiB));
        $this->assertEquals(0.0009765625, ByteUnitConverter::conversion(1, BinaryByteUnit::KiB, BinaryByteUnit::MiB));

        $instance = new ByteUnitConverter(1099511627776);

        $this->assertEquals(8.673617379884035E-19, $instance->toQiB());
        $this->assertEquals(8.881784197001252E-16, $instance->toRiB());
        $this->assertEquals(9.094947017729282E-13, $instance->toYiB());
        $this->assertEquals(9.313225746154785E-10, $instance->toZiB());
        $this->assertEquals(9.5367431640625E-7, $instance->toEiB());
        $this->assertEquals(0.0009765625, $instance->toPiB());
        $this->assertEquals(1, $instance->toTiB());
        $this->assertEquals(1024, $instance->toGiB());
        $this->assertEquals(1048576, $instance->toMiB());
        $this->assertEquals(1073741824, $instance->toKiB());
    }

    public function testConversionBetweenDecimalByteUnits()
    {
        $this->assertEquals(1000, ByteUnitConverter::conversion(1, DecimalByteUnit::MB, DecimalByteUnit::KB));
        $this->assertEquals(1, ByteUnitConverter::conversion(1000, DecimalByteUnit::KB, DecimalByteUnit::MB));
        $this->assertEquals(0.001, ByteUnitConverter::conversion(1, DecimalByteUnit::KB, DecimalByteUnit::MB));

        $instance = new ByteUnitConverter(1000000000000);

        $this->assertEquals(1.0E-18, $instance->toQB());
        $this->assertEquals(1.0E-15, $instance->toRB());
        $this->assertEquals(1.0E-12, $instance->toYB());
        $this->assertEquals(1.0E-9, $instance->toZB());
        $this->assertEquals(1.0E-6, $instance->toEB());
        $this->assertEquals(0.001, $instance->toPB());
        $this->assertEquals(1, $instance->toTB());
        $this->assertEquals(1000, $instance->toGB());
        $this->assertEquals(1000000, $instance->toMB());
        $this->assertEquals(1000000000, $instance->toKB());
    }

    public function testConversionBetweenDifferentMetricSystems()
    {
        $this->assertEquals(1048.576, ByteUnitConverter::conversion(1, BinaryByteUnit::MiB, DecimalByteUnit::KB));
        $this->assertEquals(1.024, ByteUnitConverter::conversion(1000, BinaryByteUnit::KiB, DecimalByteUnit::MB));
        $this->assertEquals(1.048576, ByteUnitConverter::conversion(1024, BinaryByteUnit::KiB, DecimalByteUnit::MB));
    }

    public function testToBitsFromOneByteEquals8()
    {
        $this->assertEquals(
            8,
            ByteUnitConverter::toBitsFromUnit(1, BinaryByteUnit::B)
        );
    }

    public function testToBytesFromOneByteEqualsSame()
    {
        $this->assertEquals(
            1,
            ByteUnitConverter::toBytesFromUnit(1, BinaryByteUnit::B)
        );
    }

    public function testToBytesFromFloatAsByteUnitThrowsException()
    {
        $this->expectExceptionObject(new \Exception('Bytes cannot be a float unit.'));

        ByteUnitConverter::toBytesFromUnit(0.1, BinaryByteUnit::B);
    }

    public function testToBitsFromFloatAsByteUnitThrowsException()
    {
        $this->expectExceptionObject(new \Exception('Bytes cannot be a float unit.'));

        ByteUnitConverter::toBitsFromUnit(0.1, BinaryByteUnit::B);
    }
}
