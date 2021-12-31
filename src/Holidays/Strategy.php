<?php
declare(strict_types=1);

namespace BusinessDays\Holidays;

use BusinessDays\Day;
use DateInterval;
use DateTimeImmutable;

abstract class Strategy
{
    /** @var DayOfMonth[] */
    protected array $permanentHolidays;

    abstract public function isMovableHoliday(Day $day): bool;

    abstract protected function regionCodeInternal(): string;

    public function isPermanentHoliday(Day $day): bool
    {
        foreach ($this->permanentHolidays as $eachHoliday) {
            if ($eachHoliday->sameDay($day)) {
                return true;
            }
        }

        return false;
    }

    public function same(Strategy $strategy): bool
    {
        return $this->regionCode() === $strategy->regionCode();
    }

    public function regionCode(): string
    {
        return $this->regionCodeInternal();
    }

    protected function isEaster(Day $day): bool
    {
        return $this->isDayAfterEaster($day, 0);
    }

    protected function isEasterMonday(Day $day): bool
    {
        return $this->isDayAfterEaster($day, 1);
    }

    protected function isWhitSunday(Day $day): bool
    {
        return $this->isDayAfterEaster($day, 49);
    }

    protected function isWhitMonday(Day $day): bool
    {
        return $this->isDayAfterEaster($day, 50);
    }

    protected function isAscensionDay(Day $day): bool
    {
        return $this->isDayAfterEaster($day, 39);
    }

    protected function isGodsBody(Day $day): bool
    {
        return $this->isDayAfterEaster($day, 60);
    }

    private function isDayAfterEaster(Day $day, int $daysAfterEaster): bool
    {
        $checkedTimestamp = strtotime($daysAfterEaster .' day', $this->getEasterTimestamp($day));
        $checkedDay = (int) date("j", $checkedTimestamp);
        $checkedMonth = (int) date("n", $checkedTimestamp);
        return ($day->dayNumber() === $checkedDay && $day->monthNumber() === $checkedMonth);
    }

    private function getEasterTimestamp(Day $day): int
    {
        $base = new DateTimeImmutable($day->yearNumber() . "-03-21");
        $daysAfterMarch21 = easter_days($day->yearNumber());
        $easterDay =  $base->add(new DateInterval("P{$daysAfterMarch21}D"));
        return $easterDay->getTimestamp();
    }
}