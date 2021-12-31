<?php
declare(strict_types=1);

namespace BusinessDays\Holidays;

use BusinessDays\Day;
use BusinessDays\Exceptions\WrongDayForMonthException;
use BusinessDays\Exceptions\WrongMonthException;

class DayOfMonth
{
    /**
     * @throws WrongDayForMonthException
     * @throws WrongMonthException
     */
    public function __construct(
        private int $dayNumber,
        private int $monthNumber
    ) {
        if ($dayNumber < 1 || $dayNumber > 31) {
            throw new WrongDayForMonthException();
        }

        if ($monthNumber < 1 || $monthNumber > 12) {
            throw new WrongMonthException();
        }
    }

    public function dayNumber(): int
    {
        return $this->dayNumber;
    }

    public function monthNumber(): int
    {
        return $this->monthNumber;
    }

    public function sameDay(Day $day): bool
    {
        return $this->dayNumber === $day->dayNumber() && $this->monthNumber === $day->monthNumber();
    }
}