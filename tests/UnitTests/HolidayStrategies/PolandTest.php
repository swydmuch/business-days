<?php
declare(strict_types=1);

namespace BusinessDays\Tests\HolidayStrategies;

use BusinessDays\Holidays\Poland;
use BusinessDays\Holidays\Strategy;

class PolandTest extends StrategyTest
{
    public function createStrategy(): Strategy
    {
        return new Poland();
    }

    public function regionCode(): string
    {
        return 'pl';
    }

    public function typeOfStrategy(): string
    {
        return Poland::class;
    }

    /**
     * @return string[][]
     */
    public function permanentHolidaysProvider(): array
    {
        return [
            'New Year' => ['2019-01-01'],
            'Three Kings' => ['2020-01-06'],
            'Labour Day' => ['2019-05-01'],
            'Constitution Day' => ['2019-05-03'],
            'Poland Army Day' => ['2019-08-15'],
            'All the Saints' => ['2019-11-01'],
            'Independence Day' => ['2019-11-11'],
            'First day of Christmas' => ['2019-12-25'],
            'Second day of Christmas' => ['2019-12-26']
        ];
    }

    /**
     * @return string[][]
     */
    public function movableHolidaysProvider(): array
    {
        return [
            'Easter 2019' => ['2019-04-21'],
            'Easter Monday 2019' => ['2019-04-22'],
            'Easter 2020' => ['2020-04-12'],
            'Easter Monday 2020' => ['2020-04-13'],
            'Whit Sunday 2019' => ['2019-06-09'],
            'Whit Sunday 2020' => ['2020-05-31'],
            'Gods Body' => ['2019-06-20']
        ];
    }

    /**
     * @return string[][]
     */
    public function notPermanentHolidaysProvider(): array
    {
        return [
            'Before Labour Day' => ['2019-04-30'],
            'After Labour Day' => ['2019-05-02'],
        ];
    }

    /**
     * @return string[][]
     */
    public function notMovableHolidaysProvider(): array
    {
        return [
            'Before Gods Body' => ['2019-06-19'],
            'After Gods Body' => ['2019-06-21']
        ];
    }
}