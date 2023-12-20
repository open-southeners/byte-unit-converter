<?php

namespace OpenSoutheners\ByteUnitConverter\Attributes;

use Attribute;
use OpenSoutheners\ByteUnitConverter\MetricSystem;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class AsByteUnit
{
    public function __construct(
        public int $weight,
        public ?string $prefix = null,
        public ?MetricSystem $metric = null
    ) {
        //
    }
}
