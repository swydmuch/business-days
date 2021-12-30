<?php
declare(strict_types=1);

namespace BusinessDays;

use BusinessDays\Exceptions\WrongDayForMonthException;
use BusinessDays\Exceptions\WrongMonthException;
use BusinessDays\Holidays\Strategy;
use BusinessDays\Holidays\StrategyFactory;
use DateTimeImmutable;

class Day
{
    /**
     * @throws WrongDayForMonthException
     * @throws WrongMonthException
     */
    final public function __construct(
        private DateTimeImmutable $dateTime,
        private Strategy $holidayStrategy
    ) { }

    static public function createFromString(string $date, string $regionCode): static
    {
        $strategy = StrategyFactory::createFromRegionCode($regionCode);
        return new static(new DateTimeImmutable($date), $strategy);
    }

    public function isBusiness(): bool
    {
        return (
            !$this->isWeekendDay()
            && !$this->holidayStrategy->isPermanentHoliday($this)
            && !$this->holidayStrategy->isMovableHoliday($this));
    }

    public function isGreaterThan(Day $date): bool
    {
        return ($this->timestamp() > $date->timestamp());
    }

    public function equals(Day $date): bool
    {
        return ($this->timestamp() === $date->timestamp());
    }

    public function nextBusinessDay(): Day
    {
        $days = 0;
        do {
            $days++;
            $nextDate = $this->dayAfter($days);
        } while (!$nextDate->isBusiness());

        return $nextDate;
    }

    public function monthNumber(): int
    {
        return (int) $this->dateTime->format("n");
    }

    public function dayNumber(): int
    {
        return (int) $this->dateTime->format("j");
    }

    public function yearNumber(): int
    {
        return (int) $this->dateTime->format("Y");
    }

    public function holidayStrategy(): Strategy
    {
        return $this->holidayStrategy;
    }

    public function sameHolidayStrategy(Day $dayToCompare): bool
    {
        return $this->holidayStrategy->same($dayToCompare->holidayStrategy());
    }

    private function timestamp(): int
    {
        return $this->dateTime->getTimestamp();
    }

    private function dayAfter(int $days): Day
    {
        return new static($this->dateTime->modify( $days . ' day'), $this->holidayStrategy);
    }

    private function isWeekendDay(): bool
    {
        $dayName = $this->dateTime->format("l");
        $weekendDays = ['Saturday', 'Sunday'];
        return in_array($dayName, $weekendDays, true);
    }
}