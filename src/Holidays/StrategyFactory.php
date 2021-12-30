<?php
declare(strict_types=1);

namespace BusinessDays\Holidays;

use BusinessDays\Exceptions\RegionWithoutDefinedStrategy;

class StrategyFactory
{
    public static function createFromRegionCode(string $regionCode): Strategy
    {
        $strategies = [
            'pl' => Poland::class,
            'nl' => Netherlands::class
        ];

        if (!array_key_exists($regionCode, $strategies)) {
            throw new RegionWithoutDefinedStrategy();
        }

        return new $strategies[$regionCode]();
    }
}