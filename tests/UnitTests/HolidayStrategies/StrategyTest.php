<?php
declare(strict_types=1);

namespace BusinessDays\Tests\HolidayStrategies;

use BusinessDays\Holidays\Strategy;
use BusinessDays\Support\MotherObjects\Day;
use PHPUnit\Framework\TestCase;

abstract class StrategyTest extends TestCase
{

    /**
     * @dataProvider permanentHolidaysProvider
     */
    public function testIsPermanentHoliday_PermanentHoliday_ReturnTrue(string $date): void
    {
        //arrange
        $strategy = $this->createStrategy();
        $day = Day::createFromStringWithSpecificStrategy($date,$strategy);

        //act
        $isHoliday = $strategy->isPermanentHoliday($day);

        //assert
        $this->assertTrue($isHoliday);
    }

    /**
     * @dataProvider notPermanentHolidaysProvider
     */
    public function testIsNotPermanentHoliday_RegularDay_ReturnFalse(string $date): void
    {
        //arrange
        $strategy = $this->createStrategy();
        $day = Day::createFromStringWithSpecificStrategy($date,$strategy);

        //act
        $isHoliday = $strategy->isPermanentHoliday($day);

        //assert
        $this->assertFalse($isHoliday);
    }

    /**
     * @dataProvider movableHolidaysProvider
     */
    public function testIsMovableHoliday_Movable_ReturnTrue(string $date): void
    {
        //arrange
        $strategy = $this->createStrategy();
        $day = Day::createFromStringWithSpecificStrategy($date, $strategy);

        //act
        $isHoliday = $strategy->isMovableHoliday($day);

        //assert
        $this->assertTrue($isHoliday);
    }

    /**
     * @dataProvider notMovableHolidaysProvider
     */
    public function testIsNotMovableHoliday_RegularDay_ReturnFalse(string $date): void
    {
        //arrange
        $strategy = $this->createStrategy();
        $day = Day::createFromStringWithSpecificStrategy($date,$strategy);

        //act
        $isHoliday = $strategy->isMovableHoliday($day);

        //assert
        $this->assertFalse($isHoliday);
    }

    public function testComparing_Same_ReturnTrue():void
    {
        //arrange
        $first = $this->createStrategy();
        $second = $this->createStrategy();

        //act
        $isSame = $first->same($second);

        //assert
        $this->assertTrue($isSame);

    }

    public function testCreationFromString_BusinessDay_ProperClassInstance(): void
    {
        //act
        $day = \BusinessDays\Day::createFromString(Day::DEFAULT_DATE, $this->regionCode());
        $strategy = $day->holidayStrategy();

        //assert
        $this->assertInstanceOf($this->typeOfStrategy(), $strategy);
    }

    public function testGettingRegionCode_RegionCode_ProperRegionCode(): void
    {
        //act
        $day = \BusinessDays\Day::createFromString(Day::DEFAULT_DATE, $this->regionCode());
        $strategy = $day->holidayStrategy();

        //assert
        $this->assertEquals($this->regionCode(), $strategy->regionCode());
    }

    abstract function regionCode(): string;

    abstract function typeOfStrategy(): string;

    abstract function createStrategy(): Strategy;
}