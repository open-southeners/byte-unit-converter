<?php

namespace OpenSoutheners\ByteUnitConverter;

trait ComparesByteUnits
{
    /**
     * Check wether current unit instance is higher than specified unit.
     */
    public function higherThan(ByteUnit $unit): bool
    {
        return $this->value > $unit->value;
    }

    /**
     * Get place difference to unit.
     */
    public function toUnit(ByteUnit $unit): int
    {
        return $this->value - $unit->value;
    }
}
