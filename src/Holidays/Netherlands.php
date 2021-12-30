<?php
declare(strict_types=1);

namespace BusinessDays\Holidays;

use BusinessDays\Day;

class Netherlands extends Strategy
{
    public function __construct()
    {
        $newYear = new DayOfMonth(1, 1);
        $kingsDay = new DayOfMonth(27,4);
        $christmasDay = new DayOfMonth(25, 12);
        $secondDayOfChristmas = new DayOfMonth(26, 12);

        $this->permanentHolidays = [
            $newYear,
            $kingsDay,
            $christmasDay,
            $secondDayOfChristmas
        ];
    }

    public function isMovableHoliday(Day $day): bool
    {
        return (
            $this->isEaster($day)
            || $this->isEasterMonday($day)
            || $this->isWhitSunday($day)
            || $this->isWhitMonday($day)
            || $this->isAscensionDay($day)
        );
    }

    protected function regionCodeInternal(): string
    {
        return 'nl';
    }
}