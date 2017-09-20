<?php

namespace Neos\ContentRepository\Domain\ValueObject;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */
use Neos\Utility\Arrays;

/**
 * A point in the dimension space with coordinates DimensionName => DimensionValue.
 *
 * E.g.: [language => es, country => ar]
 */
final class DimensionSpacePoint implements \JsonSerializable
{
    /**
     * @var array|DimensionValue[]
     */
    private $coordinates;

    /**
     * @param array $coordinates
     */
    public function __construct(array $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @param array $legacyDimensionValues
     * @return DimensionSpacePoint
     */
    public static function fromLegacyDimensionArray(array $legacyDimensionValues): DimensionSpacePoint
    {
        $coordinates = [];
        foreach ($legacyDimensionValues as $dimensionName => $rawDimensionValues) {
            $coordinates[$dimensionName] = new DimensionValue(reset($rawDimensionValues));
        }

        return new DimensionSpacePoint($coordinates);
    }

    /**
     * @return array|DimensionValue[]
     */
    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        $identityComponents = $this->coordinates;
        Arrays::sortKeysRecursively($identityComponents);

        return md5(json_encode($identityComponents));
    }

    /**
     * @return array
     */
    public function toLegacyDimensionArray(): array
    {
        $legacyDimensions = [];
        foreach ($this->coordinates as $dimensionName => $dimensionValue) {
            $legacyDimensions[$dimensionName] = [$dimensionValue];
        }

        return $legacyDimensions;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['coordinates' => $this->coordinates];
    }

    public function __toString(): string
    {
        return 'dimension space point:' . json_encode($this->coordinates);
    }
}
