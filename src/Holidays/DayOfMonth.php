<?php
declare(strict_types=1);

namespace BusinessDays\Holidays;

use BusinessDays\Exceptions\WrongDayForMonthException;
use BusinessDays\Exceptions\WrongMonthException;

class DayOfMonth
{
    /**
     * @throws WrongDayForMonthException
     * @throws WrongMonthException
     */
    public function __construct(
        private int $day,
        private int $month
    ) {
        if ($day < 1 || $day > 31) {
            throw new WrongDayForMonthException();
        }

        if ($month < 1 || $month > 12) {
            throw new WrongMonthException();
        }
    }

    public function dayNumber(): int
    {
        return $this->day;
    }

    public function monthNumber(): int
    {
        return $this->month;
    }
}