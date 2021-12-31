<?php
declare(strict_types=1);

namespace BusinessDays\Support\MotherObjects;

use BusinessDays\Day as dayToCreate;
use BusinessDays\Holidays\Strategy;

class Day
{
    const DEFAULT_REGION_CODE = 'pl';

    const DEFAULT_DATE = '2019-04-01';

    const SUNDAY_DATE = '2021-12-11';

    public static function createFromStringWithSpecificStrategy(string $date, Strategy $strategy): dayToCreate
    {
        return new dayToCreate(new \DateTimeImmutable($date), $strategy);
    }
}