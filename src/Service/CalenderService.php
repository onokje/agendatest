<?php


namespace App\Service;


use App\Entity\AgendaEvent;
use App\Repository\AgendaEventRepository;
use Symfony\Component\Security\Core\Security;

class CalenderService
{
    private AgendaEventRepository $repository;
    private Security $security;
    private CalendarHelper $calendarHelper;

    const EVENT_LENGTH = '2:00';
    const BREAK_MINUTES = 30;

    public function __construct(AgendaEventRepository $repository, CalendarHelper $calendarHelper, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
        $this->calendarHelper = $calendarHelper;
    }

    private function buildCalendar(\DateTimeImmutable $startDate)
    {
        $daysArray = [];

        for ($day = 0; $day < 7; $day++) {
            $date = $startDate->add(new \DateInterval(sprintf("P%dD", $day)));

            $events = $this->repository->getItemsByDateAndUser($date, $this->security->getUser());

            $daysArray[] = ['date' => $date, 'events' => $events, 'availableSlots' => $this->determineAvailableTimeSlots($events, $date)];
        }

        return $daysArray;
    }

    /**
     * This method works by checking the available time before each event. This assumes that events are order by startTime.
     * @param $events AgendaEvent[]
     * @return array of slots (['start' => 'starttime', 'end' => 'endtime'])
     */
    private function determineAvailableTimeSlots($events, \DateTimeInterface $date): array
    {
        if (!count($events)) {
            return [[
                'start' => '7:00',
                'end' => '17:00',
                'startDateTime' => new \DateTimeImmutable(sprintf('%s %s:00', $date->format('Y-m-d'), '7:00'))
            ]];
        }

        $slots = [];
        $nextAvailable = $this->calendarHelper->convertTimeToMinutes('7:00');

        foreach ($events as $event) {
            $startTimeInMins = $this->calendarHelper->convertTimeToMinutes($event->getStartTime());
            $newNextAvailable = $nextAvailable + $this->calendarHelper->convertTimeToMinutes(self::EVENT_LENGTH) + self::BREAK_MINUTES;
            if ($startTimeInMins > $newNextAvailable && $newNextAvailable < $this->calendarHelper->convertTimeToMinutes('17:00')) {
                $startTime = $this->calendarHelper->convertMinutesToTime($nextAvailable);
                $slots[] = [
                    'start' => $startTime,
                    'end' => $this->calendarHelper->convertMinutesToTime($startTimeInMins - self::BREAK_MINUTES),
                    'startDateTime' => new \DateTimeImmutable(sprintf('%s %s:00', $date->format('Y-m-d'), $startTime))
                ];

            }
            $nextAvailable = $this->calendarHelper->convertTimeToMinutes($event->getEndTime()) + 30;
        }

        // check if there is enough time after the last event
        if ($nextAvailable < $this->calendarHelper->convertTimeToMinutes('15:00')) {
            $startTime = $this->calendarHelper->convertMinutesToTime($nextAvailable);
            $slots[] = [
                'start' => $startTime,
                'end' => '17:00',
                'startDateTime' => new \DateTimeImmutable(sprintf('%s %s:00', $date->format('Y-m-d'), $startTime))
            ];
        }

        return $slots;
    }

    public function getView(int $year, int $month, int $day): array
    {
        $date = new \DateTimeImmutable("$year-$month-$day");
        $today = new \DateTimeImmutable();
        return [
            'days' => $this->buildCalendar($date),
            'prevWeek' => $date->sub(new \DateInterval("P1W")),
            'nextWeek' => $date->add(new \DateInterval("P1W")),
            'today' => $today
        ];
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addEvent(AgendaEvent $agendaEvent)
    {
        $this->repository->save($agendaEvent);
    }
}