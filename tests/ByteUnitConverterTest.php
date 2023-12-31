<?php

declare(strict_types=1);

namespace OpenSoutheners\ByteUnitConverter\Tests;

use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\ByteUnitConverter\ByteUnitConverter;
use OpenSoutheners\ByteUnitConverter\MetricSystem;
use PHPUnit\Framework\TestCase;

enum MyOwnByteUnit: string
{
    case GB = '1';
}

class ByteUnitConverterTest extends TestCase
{
    public function testNewInstanceUsingNotNumericStringThrowsException()
    {
        $this->expectExceptionObject(new \Exception('Not numeric value as bytes not supported.'));

        ByteUnitConverter::new('hello 12 worlds');
    }

    public function testFromMethodReturnsSelfInstance()
    {
        $this->assertInstanceOf(ByteUnitConverter::class, ByteUnitConverter::from('1', ByteUnit::GiB));
    }

    public function testConversionBetweenBinaryByteUnits()
    {
        $this->assertEquals('1024', ByteUnitConverter::from('1', ByteUnit::MiB)->to(ByteUnit::KiB)->asRound()->getValue());
        $this->assertEquals('1', ByteUnitConverter::from('1024', ByteUnit::KiB)->to(ByteUnit::MiB)->asRound()->getValue());
        $this->assertEquals('0.000976563', ByteUnitConverter::from('1', ByteUnit::KiB)->to(ByteUnit::MiB)->setPrecision(10)->getValue());

        $bytes = '1099511627776';

        $this->assertEquals('0.0000000000000000008673617381445643', ByteUnitConverter::new($bytes)->setPrecision(34)->asRound(false)->toQiB()->getValue());
        $this->assertEquals('0.0000000000000008881784191874108', ByteUnitConverter::new($bytes)->setPrecision(31)->asRound(false)->toRiB()->getValue());
        $this->assertEquals('0.0000000000009094947014830074', ByteUnitConverter::new($bytes)->setPrecision(28)->asRound(false)->toYiB()->getValue());
        $this->assertEquals('0.0000000009313225751814163', ByteUnitConverter::new($bytes)->setPrecision(25)->asRound(false)->toZiB()->getValue());
        $this->assertEquals('0.00000095367432021694', ByteUnitConverter::new($bytes)->setPrecision(20)->asRound(false)->toEiB()->getValue());
        $this->assertEquals('0.0009765624', ByteUnitConverter::new($bytes)->setPrecision(10)->asRound(false)->toPiB()->getValue());
        $this->assertEquals('1', ByteUnitConverter::new($bytes)->toTiB()->asRound()->getValue());
        $this->assertEquals('1024', ByteUnitConverter::new($bytes)->toGiB()->asRound()->getValue());
        $this->assertEquals('1048576', ByteUnitConverter::new($bytes)->toMiB()->asRound()->getValue());
        $this->assertEquals('1073741824', ByteUnitConverter::new($bytes)->toKiB()->asRound()->getValue());
    }

    public function testConversionBetweenDecimalByteUnits()
    {
        $this->assertEquals('1000', ByteUnitConverter::from('1', ByteUnit::MB)->to(ByteUnit::KB)->asRound()->getValue());
        $this->assertEquals('1', ByteUnitConverter::from('1000', ByteUnit::KB)->to(ByteUnit::MB)->asRound()->getValue());
        $this->assertEquals('0.001', ByteUnitConverter::from('1', ByteUnit::KB)->to(ByteUnit::MB)->setPrecision(3)->getValue());

        $bytes = '1000000000000';

        $this->assertEquals('0.0000000000000000009', ByteUnitConverter::new($bytes)->toQB()->setPrecision(19)->asRound(false)->getValue());
        $this->assertEquals('0.0000000000000009', ByteUnitConverter::new($bytes)->toRB()->setPrecision(16)->asRound(false)->getValue());
        $this->assertEquals('0.000000000001', ByteUnitConverter::new($bytes)->toYB()->setPrecision(13)->asRound(false)->getValue());
        $this->assertEquals('0.000000001', ByteUnitConverter::new($bytes)->toZB()->setPrecision(10)->asRound(false)->getValue());
        $this->assertEquals('0.000001', ByteUnitConverter::new($bytes)->toEB()->setPrecision(7)->asRound(false)->getValue());
        $this->assertEquals('0.001', ByteUnitConverter::new($bytes)->toPB()->setPrecision(3)->asRound(false)->getValue());
        $this->assertEquals('1', ByteUnitConverter::new($bytes)->toTB()->asRound()->getValue());
        $this->assertEquals('1000', ByteUnitConverter::new($bytes)->toGB()->asRound()->getValue());
        $this->assertEquals('1000000', ByteUnitConverter::new($bytes)->toMB()->asRound()->getValue());
        $this->assertEquals('1000000000', ByteUnitConverter::new($bytes)->toKB()->asRound()->getValue());
    }

    public function testConversionBetweenDifferentMetricSystems()
    {
        $this->assertEquals('1048.58', ByteUnitConverter::from('1', ByteUnit::MiB)->to(ByteUnit::KB)->getValue());
        $this->assertEquals('1.02', ByteUnitConverter::from('1000', ByteUnit::KiB)->to(ByteUnit::MB)->getValue());
        $this->assertEquals('1.049', ByteUnitConverter::from('1024', ByteUnit::KiB)->to(ByteUnit::MB)->getValue());
    }

    public function testToBitsFromOneByteEquals8()
    {
        $this->assertEquals(
            '8',
            ByteUnitConverter::from('1', ByteUnit::B)->usingBits()->getValue()
        );
    }

    public function testToBytesFromOneByteEqualsSame()
    {
        $this->assertEquals(
            '1',
            ByteUnitConverter::from('1', ByteUnit::B)->usingBytes()->getValue()
        );
    }

    public function testConversionToStringSerializesResultWithUnitAppended()
    {
        $this->assertEquals(
            '1 KiB',
            (string) ByteUnitConverter::new('1024')->asRound()->toKiB()
        );

        $this->assertEquals(
            '8 KiB',
            (string) ByteUnitConverter::new('1024')->asRound()->usingBits()->toKiB()
        );
    }

    public function testConversionToStringUsingUnitLabelSerializesResultWithUnitLabelAppended()
    {
        $this->assertEquals(
            '1 kibibyte',
            (string) ByteUnitConverter::new('1024')->useUnitLabel()->asRound()->toKiB()
        );

        $this->assertEquals(
            '8 kibibits',
            (string) ByteUnitConverter::new('1024')->useUnitLabel()->asRound()->usingBits()->toKiB()
        );
    }

    public function testConversionToStringSerializesResultWithBitsUnitAppendedWhenFromBaseUnit()
    {
        $this->assertEquals(
            '8192 b',
            (string) ByteUnitConverter::new('1024')->asRound()->usingBits()
        );
    }

    public function testConversionToStringSerializesResultWithBytesUnitAppendedWhenFromBaseUnit()
    {
        $this->assertEquals(
            '1024 B',
            (string) ByteUnitConverter::new('1024')->asRound()->usingBytes()
        );
    }

    public function testConversionToArraySerializesResultToArrayWithUnitAndValue()
    {
        $this->assertEquals(
            [
                'unit' => 'KiB',
                'unit_label' => 'kibibytes',
                'value' => '1',
            ],
            ByteUnitConverter::new('1024')->asRound()->toKiB()->toArray()
        );
    }

    public function testConversionSerializesObjectInstance()
    {
        $instance = ByteUnitConverter::new('1024')->asRound()->toKiB();

        $data = serialize($instance);

        $deserialized = unserialize($data);

        $this->assertEquals((string) $instance, (string) $deserialized->asRound());
    }

    public function testConversionToBytesDoesNotGiveDecimals()
    {
        $this->assertEquals(
            '1000 B',
            (string) ByteUnitConverter::new('1000')
        );
    }

    public function testConversionToNearestUnitPrintsConvertedByteUnitAndMetricSystem()
    {
        $this->assertEquals(
            '1 KB',
            (string) ByteUnitConverter::new('1000')->nearestUnit()
        );

        $this->assertEquals(
            '1.02 KB',
            (string) ByteUnitConverter::new('1024')->nearestUnit()
        );

        $this->assertEquals(
            '1 KiB',
            (string) ByteUnitConverter::new('1024')->nearestUnit(MetricSystem::Binary)
        );

        $this->assertEquals(
            '1000 B',
            (string) ByteUnitConverter::new('1000')->nearestUnit(MetricSystem::Binary)
        );

        $this->assertEquals(
            '0.977 KiB',
            (string) ByteUnitConverter::new('1000')->nearestUnit(MetricSystem::Binary, ByteUnit::KiB)
        );
    }

    public function testConversionAsRoundWhenValueIsZeroReturnsAtLeastOneDecimal()
    {
        $this->assertEquals(
            '0.5 KiB',
            (string) ByteUnitConverter::new(500)->asRound()->toKiB()
        );
    }

    public function testConversionOfOneMegabyteAddingOneGigabyteResultsInOneThousandOneMegabytes()
    {
        $this->assertEquals(
            '1001 MB',
            (string) ByteUnitConverter::from(1, ByteUnit::MB)->add('1', ByteUnit::GB)->asRound()->toMB()
        );
    }

    public function testConversionOfOneGigabyteSubtractingHalfGigabyteResultsInHalfGigabyte()
    {
        $this->assertEquals(
            '0.5 GB',
            (string) ByteUnitConverter::from(1, ByteUnit::GB)->subtract(0.5, ByteUnit::GB)->asRound()->toGB()
        );
    }
}
