<?php

namespace OpenSoutheners\ByteUnitConverter\Tests;

use OpenSoutheners\ByteUnitConverter\BinaryByteUnit;
use OpenSoutheners\ByteUnitConverter\DecimalByteUnit;
use PHPUnit\Framework\TestCase;

class ByteUnitTest extends TestCase
{
    public function testGigabyteHigherThanTerabyteReturnsFalse()
    {
        $this->assertFalse(DecimalByteUnit::GB->higherThan(DecimalByteUnit::TB));
    }

    public function testGibibyteHigherThanTerabyteReturnsFalse()
    {
        $this->assertFalse(BinaryByteUnit::GiB->higherThan(DecimalByteUnit::TB));
    }

    public function testGigabyteHigherThanGigabyteReturnsFalse()
    {
        $this->assertFalse(DecimalByteUnit::GB->higherThan(DecimalByteUnit::GB));
    }

    public function testGibibyteHigherThanGigabyteReturnsFalse()
    {
        $this->assertFalse(BinaryByteUnit::GiB->higherThan(DecimalByteUnit::GB));
    }

    public function testTerabyteHigherThanGigabyteReturnsTrue()
    {
        $this->assertTrue(DecimalByteUnit::TB->higherThan(DecimalByteUnit::GB));
    }

    public function testTerabyteToGigabyteReturnsOneAsPositiveNumber()
    {
        $this->assertIsNumeric(DecimalByteUnit::TB->toUnit(DecimalByteUnit::GB));
        $this->assertEquals(1, DecimalByteUnit::TB->toUnit(DecimalByteUnit::GB));
    }

    public function testGigabyteToTerabyteReturnsOneAsNegativeNumber()
    {
        $this->assertIsNumeric(DecimalByteUnit::GB->toUnit(DecimalByteUnit::TB));
        $this->assertEquals(-1, DecimalByteUnit::GB->toUnit(DecimalByteUnit::TB));
    }
}
