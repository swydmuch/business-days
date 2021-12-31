<?php
declare(strict_types=1);

namespace BusinessDays\Tests;

use BusinessDays\Support\MotherObjects\Day;
use PHPUnit\Framework\TestCase;
use BusinessDays\Holidays\Strategy;

class DayTest extends TestCase
{
    /**
     * @dataProvider businessDaysProvider
     */
    public function testIsBusiness_MondayToFriday_ReturnTrue(string $date): void
    {
        //arrange
        $day = Day::createFromStringWithSpecificStrategy($date, $this->createAlwaysNotHolidayStrategy());

        //act
         $isBusiness = $day->isBusiness();

        //assert
        $this->assertTrue($isBusiness);

    }

    /**
     * @dataProvider weekendDaysProvider
     */
    public function testIsBusiness_SaturdaySunday_ReturnFalse(string $date): void
    {
        //arrange
        $day = Day::createFromStringWithSpecificStrategy($date, $this->createAlwaysNotHolidayStrategy());

        //act
        $isBusiness = $day->isBusiness();

        //assert
        $this->assertFalse($isBusiness);

    }

    public function testIsBusiness_PermanentHoliday_ReturnFalse(): void
    {
        //arrange
        $day = Day::createFromStringWithSpecificStrategy(Day::DEFAULT_DATE, $this->createAlwaysPermanentHolidayStrategy());

        //act
        $isBusiness = $day->isBusiness();

        //assert
        $this->assertFalse($isBusiness);

    }

    public function testIsBusiness_MovableHoliday_ReturnFalse(): void
    {
        //arrange
        $day = Day::createFromStringWithSpecificStrategy(Day::DEFAULT_DATE, $this->createAlwaysMovableHolidayStrategy());

        //act
        $isBusiness = $day->isBusiness();

        //assert
        $this->assertFalse($isBusiness);
    }

    public function testIsGreaterThan_LessDate_returnTrue(): void
    {
        //arrange
        $baseDate = Day::createFromStringWithSpecificStrategy('2019-05-14', $this->createAlwaysNotHolidayStrategy());
        $lessDate = Day::createFromStringWithSpecificStrategy('2019-05-13', $this->createAlwaysNotHolidayStrategy());

        //act
        $isGrater = $baseDate->isGreaterThan($lessDate);

        //assert
        $this->assertTrue($isGrater);
    }

    public function testIsGraterThan_GraterDate_returnFalse(): void
    {
        //arrange
        $baseDate = Day::createFromStringWithSpecificStrategy('2019-05-13', $this->createAlwaysNotHolidayStrategy());
        $greaterDate = Day::createFromStringWithSpecificStrategy('2019-05-14', $this->createAlwaysNotHolidayStrategy());

        //act
        $isGrater = $baseDate->isGreaterThan($greaterDate);

        //assert
        $this->assertFalse($isGrater);
    }

    public function testIsGraterThan_SameDate_returnFalse(): void
    {
        //arrange
        $baseDate = Day::createFromStringWithSpecificStrategy('2019-05-13', $this->createAlwaysNotHolidayStrategy());
        $greaterDate = Day::createFromStringWithSpecificStrategy('2019-05-13', $this->createAlwaysNotHolidayStrategy());

        //act
        $isGrater = $baseDate->isGreaterThan($greaterDate);

        //assert
        $this->assertFalse($isGrater);
    }

    public function testEquals_TwoSameDate_returnTrue(): void
    {
        //arrange
        $firstDate = Day::createFromStringWithSpecificStrategy(
            Day::DEFAULT_DATE,
            $this->createAlwaysNotHolidayStrategy()
        );
        $secondDate = Day::createFromStringWithSpecificStrategy(
            Day::DEFAULT_DATE,
            $this->createAlwaysNotHolidayStrategy()
        );

        //act
        $isEqual = $firstDate->equals($secondDate);

        //assert
        $this->assertTrue($isEqual);
    }

    public function testEquals_TwoDifferentDate_returnFalse(): void
    {
        //arrange
        $firstDate = Day::createFromStringWithSpecificStrategy('2019-05-13', $this->createAlwaysNotHolidayStrategy());
        $secondDate = Day::createFromStringWithSpecificStrategy('2019-05-14', $this->createAlwaysNotHolidayStrategy());

        //act
        $isEqual = $firstDate->equals($secondDate);

        //assert
        $this->assertFalse($isEqual);
    }

    /**
     * @dataProvider baseAndFollowingBusinessDatesProvider
     */
    public function testGettingNextBusinessDate_BaseDate_ReturnProperDate(string $base, string $expected): void
    {
        //arrange
        $baseDate = Day::createFromStringWithSpecificStrategy($base, $this->createAlwaysNotHolidayStrategy());
        $expectedDate = Day::createFromStringWithSpecificStrategy($expected, $this->createAlwaysNotHolidayStrategy());

        //act
        $isProper = $expectedDate->equals($baseDate->nextBusinessDay());

        //assert
        $this->assertTrue($isProper);
    }

    public function testComparingStrategies_DayWithSameStrategy_returnTrue(): void
    {
        //arrage
        $first = Day::createFromStringWithSpecificStrategy(
            Day::DEFAULT_DATE,
            $this->createAlwaysSameStrategy()
        );
        $second = Day::createFromStringWithSpecificStrategy(
            Day::DEFAULT_DATE,
            $this->createAlwaysSameStrategy()
        );

        //act
        $isSame = $first->sameHolidayStrategy($second);

        //assert
        $this->assertTrue($isSame);
    }

    public function testComparingStrategies_DayWithDifferentStrategy_returnFalse(): void
    {
        //arrage
        $first = Day::createFromStringWithSpecificStrategy(
            Day::DEFAULT_DATE,
            $this->createAlwaysDifferentStrategy()
        );
        $second = Day::createFromStringWithSpecificStrategy(
            Day::DEFAULT_DATE,
            $this->createAlwaysDifferentStrategy()
        );

        //act
        $isSame = $first->sameHolidayStrategy($second);

        //assert
        $this->assertFalse($isSame);
    }

    /**
     * @return string[][]
     */
    public function businessDaysProvider(): array
    {
        return [
            'Monday' => ['2019-04-01'],
            'Tuesday' => ['2019-04-02'],
            'Wednesday' => ['2019-04-03'],
            'Thursday' => ['2019-04-04'],
            'Friday' => ['2019-04-05'],
        ];
    }

    /**
     * @return string[][]
     */
    public function weekendDaysProvider(): array
    {
        return [
            'Saturday' => ['2019-04-06'],
            'Sunday' => ['2019-04-07'],
        ];
    }

    /**
     * @return string[][]
     */
    public function baseAndFollowingBusinessDatesProvider(): array
    {
        return [
            ['2019-05-13', '2019-05-14'],
            ['2019-05-10', '2019-05-13']
        ];
    }

    public function createAlwaysNotHolidayStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('same')->willReturn(true);
        $holidayStrategy->method('isPermanentHoliday')->willReturn(false);
        $holidayStrategy->method('isMovableHoliday')->willReturn(false);
        return $holidayStrategy;
    }

    public function createAlwaysPermanentHolidayStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('isPermanentHoliday')->willReturn(true);
        $holidayStrategy->method('isMovableHoliday')->willReturn(false);
        return $holidayStrategy;
    }
// TODO
    public function createAlwaysPermanenAndMovabletHolidayStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('isPermanentHoliday')->willReturn(true);
        $holidayStrategy->method('isMovableHoliday')->willReturn(true);
        return $holidayStrategy;
    }

    public function createAlwaysMovableHolidayStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('isPermanentHoliday')->willReturn(false);
        $holidayStrategy->method('isMovableHoliday')->willReturn(true);
        return $holidayStrategy;
    }

    public function createAlwaysSameStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('same')->willReturn(true);
        return $holidayStrategy;
    }

    public function createAlwaysDifferentStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('same')->willReturn(false);
        return $holidayStrategy;
    }
}