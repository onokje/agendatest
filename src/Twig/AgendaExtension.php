<?php

namespace App\Twig;

use App\Service\CalendarHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AgendaExtension extends AbstractExtension
{
    private CalendarHelper $calendarHelper;

    public function __construct(CalendarHelper $calendarHelper)
    {
        $this->calendarHelper = $calendarHelper;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('timeToOffset', [$this, 'timeToOffset']),
            new TwigFunction('slotWidth', [$this, 'slotWidth']),
        ];
    }

    /**
     * Calculate offset position for events based on start time.
     * Works only because all positioning is fixed (every hour is exactly 100px). Yes, i know that's cheating.
     * @param string $time
     * @return int
     */
    public function timeToOffset(string $time): int
    {
        $minutes = $this->calendarHelper->convertTimeToMinutes($time);

        return ($minutes - (6*60)) / 60 * 100;
    }

    public function slotWidth(array $slot): int
    {
        $startTimeInMins = $this->calendarHelper->convertTimeToMinutes($slot['start']);
        $endTimeInMins = $this->calendarHelper->convertTimeToMinutes($slot['end']);
        $slotTimenMins = $endTimeInMins - $startTimeInMins;
        return $slotTimenMins / 60 * 100;
    }
}
