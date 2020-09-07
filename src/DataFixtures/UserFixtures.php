<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const TEST_USER = 'user1';

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setName('monteur1');
        $user1->setPassword($this->userPasswordEncoder->encodePassword($user1, 'welkom123'));
        $user1->setEmail('monteur1@agenda.nl');
        $manager->persist($user1);



        $user2 = new User();
        $user2->setName('monteur2');
        $user2->setPassword($this->userPasswordEncoder->encodePassword($user2, 'welkom123'));
        $user2->setEmail('monteur2@agenda.nl');
        $manager->persist($user2);

        $manager->flush();

        $this->addReference(self::TEST_USER, $user1);

    }
}
