<?php

namespace OpenSoutheners\ByteUnitConverter\Tests;

use OpenSoutheners\ByteUnitConverter\ByteUnit;
use OpenSoutheners\ByteUnitConverter\MetricSystem;
use PHPUnit\Framework\TestCase;

class ByteUnitTest extends TestCase
{
    public function testGigabyteLowerThanTerabyteReturnsFalse()
    {
        $this->assertFalse(ByteUnit::TB->lowerThan(ByteUnit::GB));
    }

    public function testGibibyteLowerThanTerabyteReturnsFalse()
    {
        $this->assertFalse(ByteUnit::TiB->lowerThan(ByteUnit::GiB));
    }

    public function testGigabyteHigherThanTerabyteReturnsFalse()
    {
        $this->assertFalse(ByteUnit::GB->higherThan(ByteUnit::TB));
    }

    public function testGibibyteHigherThanTerabyteReturnsFalse()
    {
        $this->assertFalse(ByteUnit::GiB->higherThan(ByteUnit::TB));
    }

    public function testGigabyteHigherThanGigabyteReturnsFalse()
    {
        $this->assertFalse(ByteUnit::GB->higherThan(ByteUnit::GB));
    }

    public function testGigabyteHigherThanGibibyteReturnsFalse()
    {
        $this->assertFalse(ByteUnit::GB->higherThan(ByteUnit::GiB));
    }

    public function testTerabyteHigherThanGigabyteReturnsTrue()
    {
        $this->assertTrue(ByteUnit::TB->higherThan(ByteUnit::GB));
    }

    public function testGigabyteLowerThanTerabyteReturnsTrue()
    {
        $this->assertTrue(ByteUnit::GB->lowerThan(ByteUnit::TB));
    }

    public function testTerabyteToGigabyteReturnsOneAsPositiveNumber()
    {
        $this->assertIsNumeric(ByteUnit::TB->toUnit(ByteUnit::GB));
        $this->assertEquals(1, ByteUnit::TB->toUnit(ByteUnit::GB));
    }

    public function testGigabyteToTerabyteReturnsOneAsNegativeNumber()
    {
        $this->assertIsNumeric(ByteUnit::GB->toUnit(ByteUnit::TB));
        $this->assertEquals(-1, ByteUnit::GB->toUnit(ByteUnit::TB));
    }

    public function testGigabyteAsNumberReturnsNumericStringWithoutExponentSign()
    {
        $this->assertStringContainsString('e', ByteUnit::GB->value);
        $this->assertStringNotContainsString('e', ByteUnit::GB->asNumber());
    }

    public function testByteUnitGetMetricGetsMetricSystemUsedByUnit()
    {
        $this->assertEquals(MetricSystem::Decimal, ByteUnit::GB->getMetric());
        $this->assertEquals(MetricSystem::Binary, ByteUnit::GiB->getMetric());
    }
}
