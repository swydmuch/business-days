<?php
declare(strict_types=1);

namespace BusinessDays\Tests\HolidayStrategies;

use BusinessDays\Holidays\Netherlands;
use BusinessDays\Holidays\Strategy;

class NetherlandsTest extends StrategyTest
{
    public function createStrategy(): Strategy
    {
        return new Netherlands();
    }

    public function regionCode(): string
    {
        return 'nl';
    }

    public function typeOfStrategy(): string
    {
        return Netherlands::class;
    }

    /**
     * @return string[][]
     */
    public function permanentHolidaysProvider(): array
    {
        return [
            'New Year' => ['2021-01-01'],
            'Kings Day' => ['2021-04-27'],
            'First day of Christmas' => ['2021-12-25'],
            'Second day of Christmas' => ['2021-12-26']
        ];
    }

    /**
     * @return string[][]
     */
    public function movableHolidaysProvider(): array
    {
        return [
            'Easter 2021' => ['2021-04-04'],
            'Easter Monday 2021' => ['2021-04-05'],
            'Whit Sunday 2021' => ['2021-05-23'],
            'Whit Monday 2021' => ['2021-05-24'],
            'Ascension Day' => ['2021-05-13'],
        ];
    }

    /**
     * @return string[][]
     */
    public function notPermanentHolidaysProvider(): array
    {
        return [
            'Before New Year' => ['2020-12-31'],
            'After New Year' => ['2021-01-02'],
        ];
    }

    /**
     * @return string[][]
     */
    public function notMovableHolidaysProvider(): array
    {
        return [
            'Before Easter 2021' => ['2021-04-03'],
            'Tuesday after Easter ' => ['2021-04-06']
        ];
    }
}
