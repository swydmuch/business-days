<?php
declare(strict_types=1);

namespace BusinessDays\Holidays;

use BusinessDays\Day;


class Poland extends Strategy
{
    public function __construct()
    {
        $newYear = new DayOfMonth(1, 1);
        $epiphany = new DayOfMonth(6, 1);
        $labourDay = new DayOfMonth(1, 5);
        $constitutionDay = new DayOfMonth(3, 5);
        $armyDay = new DayOfMonth(15, 8);
        $allSaintsDay = new DayOfMonth(1, 11);
        $independenceDay = new DayOfMonth(11, 11);
        $christmasDay = new DayOfMonth(25, 12);
        $secondDayOfChristmas = new DayOfMonth(26, 12);
        $christmasEve = new DayOfMonth(24, 12, 2025);
        $this->permanentHolidays = [
            $newYear,
            $epiphany,
            $labourDay,
            $constitutionDay,
            $armyDay,
            $allSaintsDay,
            $independenceDay,
            $christmasDay,
            $secondDayOfChristmas,
            $christmasEve
        ];
    }

    public function isMovableHoliday(Day $day): bool
    {
        return (
            $this->isEaster($day)
            || $this->isEasterMonday($day)
            || $this->isGodsBody($day)
            || $this->isWhitSunday($day)
        );
    }

    protected function regionCodeInternal(): string
    {
        return 'pl';
    }
}