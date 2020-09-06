<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
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
        $user = new User();
        $user->setName('monteur1');
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'welkom123'));
        $user->setEmail('monteur1@agenda.nl');
        $manager->persist($user);

        $user = new User();
        $user->setName('monteur2');
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'welkom123'));
        $user->setEmail('monteur2@agenda.nl');
        $manager->persist($user);

        $manager->flush();
    }
}
