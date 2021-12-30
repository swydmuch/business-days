<?php
declare(strict_types=1);

namespace BusinessDays;

use BusinessDays\Exceptions\DifferentRegionDaysInRangeException;
use BusinessDays\Exceptions\EndingDayIsNotBusinessDayException;
use BusinessDays\Exceptions\StartingDayIsGreaterThanEndingException;
use BusinessDays\Exceptions\StartingDayIsNotBusinessDayException;
use BusinessDays\Exceptions\WeekendDateException;

class Range
{
    /**
     * @throws EndingDayIsNotBusinessDayException
     * @throws StartingDayIsGreaterThanEndingException
     * @throws StartingDayIsNotBusinessDayException
     * @throws DifferentRegionDaysInRangeException
     */
    final public function __construct(
        private Day $startingDay,
        private Day $endingDay
    ) {
        $this->throwExceptionIfDifferentRegion();
        $this->throwExceptionIfStartIsGreaterThanEnd();
        $this->throwExceptionIfStartingDayIsFree();
        $this->throwExceptionIfEndingDayIsFree();

    }

    public static function createFromString(string $startingDate, string $endingDate, string $regionCode): static
    {
        return new static(
            Day::createFromString($startingDate, $regionCode),
            Day::createFromString($endingDate, $regionCode)
        );
    }

    public function countBusinessDays(): int
    {
        $result = 1;
        $nextDate = $this->startingDay;
        while (!$this->endingDay->equals($nextDate)) {
            $nextDate = $nextDate->nextBusinessDay();
            $result++;
        }

        return $result;
    }

    /**
     * @return Day[]
     */
    public function businessDays(): array
    {
        $nextDate = $this->startingDay;
        $result = [
            $this->startingDay
        ];
        while (!$this->endingDay->equals($nextDate)) {
            $nextDate = $nextDate->nextBusinessDay();
            $result[] = $nextDate;
        }

        return $result;
    }

    /**
     * @throws StartingDayIsGreaterThanEndingException
     */
    private function throwExceptionIfStartIsGreaterThanEnd(): void
    {
        if ($this->startingDay->isGreaterThan($this->endingDay)) {
            throw new StartingDayIsGreaterThanEndingException();
        }
    }

    private function throwExceptionIfStartingDayIsFree(): void
    {
        if (!$this->startingDay->isBusiness()) {
            throw new StartingDayIsNotBusinessDayException();
        }
    }

    private function throwExceptionIfEndingDayIsFree(): void
    {
        if (!$this->endingDay->isBusiness()) {
            throw new EndingDayIsNotBusinessDayException();
        }
    }

    private function throwExceptionIfDifferentRegion():void
    {
        if(!$this->startingDay->sameHolidayStrategy($this->endingDay)) {
            throw new DifferentRegionDaysInRangeException();
        }
    }
}