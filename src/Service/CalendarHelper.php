<?php

namespace App\Service;

class CalendarHelper
{
    public function convertTimeToMinutes(string $time): int
    {
        list($hours, $minutes) = explode(":", $time);
        return ($hours * 60) + $minutes;
    }

    public function convertMinutesToTime(int $minutes): string
    {
        return sprintf("%d:%d",floor($minutes / 60), ($minutes % 60));
    }

}
