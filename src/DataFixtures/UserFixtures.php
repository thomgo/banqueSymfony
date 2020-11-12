<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("john.doe@gmail.com");
        $user->setFirstname("John");
        $user->setLastname("Doe");
        $user->setCity("Lille");
        $user->setCityCode("59000");
        $user->setSex("m");
        $user->setBirthDate(\DateTime::createFromFormat('d-m-Y', '15-02-1978'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'password1'
        ));

        $user2 = new User();
        $user2->setEmail("claire.dupret@gmail.com");
        $user2->setFirstname("Claire");
        $user2->setLastname("Dupret");
        $user2->setCity("Rouen");
        $user2->setCityCode("76000");
        $user2->setSex("f");
        $user2->setBirthDate(\DateTime::createFromFormat('d-m-Y', '25-10-1990'));
        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'password2'
        ));

        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();
        $this->addReference('user1', $user);
        $this->addReference('user2', $user2);
    }
}
