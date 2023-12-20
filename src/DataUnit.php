<?php

namespace OpenSoutheners\ByteUnitConverter;

use OpenSoutheners\ByteUnitConverter\Attributes\AsDataUnit;

/**
 * This enum serves as a type safety for units systems to measure data size.
 *
 * @see https://en.wikipedia.org/wiki/Byte
 * @see https://en.wikipedia.org/wiki/Bit
 *
 * @author Rubén Robles <me@d8vjork.com>
 */
enum DataUnit: string
{
    #[AsDataUnit('byte')]
    case B = '1';

    #[AsDataUnit('bit')]
    case b = '8';

    /**
     * Get data unit label.
     */
    public function getLabel(): string
    {
        $reflection = new \ReflectionEnumBackedCase($this, $this->name);

        $reflectionAttributes = $reflection->getAttributes(AsDataUnit::class);

        /** @var \ReflectionAttribute<\OpenSoutheners\ByteUnitConverter\Attributes\AsDataUnit> $dataUnitAttribute */
        $dataUnitAttribute = reset($reflectionAttributes);

        return $dataUnitAttribute->newInstance()->label;
    }
}
