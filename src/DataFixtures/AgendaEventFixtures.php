<?php

namespace App\DataFixtures;

use App\Entity\Agenda;
use App\Entity\AgendaEvent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AgendaEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $agenda = new Agenda();
        $agenda->setName('agenda1');
        $agenda->setStartsAt(new \DateTime());
        $agenda->setEndsAt(new \DateTime());

        $manager->persist($agenda);
        $user = $this->getReference(UserFixtures::TEST_USER);

        $event = new AgendaEvent();
        $event->setAgenda($agenda);
        $event->setUser($user);
        $event->setStartsAt(new \DateTime('2020-09-09 12:00:00'));
        $event->setEndsAt(new \DateTime('2020-09-09 14:00:00'));
        $event->setTitle('event1');
        $manager->persist($event);

        $event = new AgendaEvent();
        $event->setAgenda($agenda);
        $event->setUser($user);
        $event->setStartsAt(new \DateTime('2020-09-10 8:00:00'));
        $event->setEndsAt(new \DateTime('2020-09-10 10:00:00'));
        $event->setTitle('event2');
        $manager->persist($event);

        $event = new AgendaEvent();
        $event->setAgenda($agenda);
        $event->setUser($user);
        $event->setStartsAt(new \DateTime('2020-09-10 14:00:00'));
        $event->setEndsAt(new \DateTime('2020-09-10 16:00:00'));
        $event->setTitle('event3');
        $manager->persist($event);

        $event = new AgendaEvent();
        $event->setAgenda($agenda);
        $event->setUser($user);
        $event->setStartsAt(new \DateTime('2020-09-12 7:30:00'));
        $event->setEndsAt(new \DateTime('2020-09-12 9:30:00'));
        $event->setTitle('event4');
        $manager->persist($event);

        $event = new AgendaEvent();
        $event->setAgenda($agenda);
        $event->setUser($user);
        $event->setStartsAt(new \DateTime('2020-09-13 11:30:00'));
        $event->setEndsAt(new \DateTime('2020-09-13 13:30:00'));
        $event->setTitle('event5');
        $manager->persist($event);

        $event = new AgendaEvent();
        $event->setAgenda($agenda);
        $event->setUser($user);
        $event->setStartsAt(new \DateTime('2020-09-13 14:00:00'));
        $event->setEndsAt(new \DateTime('2020-09-13 16:00:00'));
        $event->setTitle('event6');
        $manager->persist($event);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
