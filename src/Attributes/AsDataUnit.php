<?php

namespace OpenSoutheners\ByteUnitConverter\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class AsDataUnit
{
    public function __construct(public string $label)
    {
        //
    }
}
