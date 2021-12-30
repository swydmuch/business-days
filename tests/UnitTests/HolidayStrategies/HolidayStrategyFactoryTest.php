<?php
declare(strict_types=1);

namespace BusinessDays\Tests\HolidayStrategies;

use BusinessDays\Exceptions\RegionWithoutDefinedStrategy;
use BusinessDays\Holidays\StrategyFactory;
use PHPUnit\Framework\TestCase;

class HolidayStrategyFactoryTest extends TestCase
{
    public function testCreatingStrategy_NotDefinedRegion_ExceptionIsThrown(): void
    {
        //arrange
        $notDefinedRegionCode = '*=<>&xyz-123&<>+=*';

        //assert
        $this->expectException(RegionWithoutDefinedStrategy::class);

        //act
        StrategyFactory::createFromRegionCode($notDefinedRegionCode);
    }
}