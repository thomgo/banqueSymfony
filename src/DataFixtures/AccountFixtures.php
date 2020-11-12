<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{

    private $faker;

    public function __construct()
    {
         $this->faker = Faker\Factory::create();;
    }

    public function load(ObjectManager $manager)
    {
        $types = ["PEL", "PER", "LDD", "Livret A", "Compte courant"];

        for ($i=0; $i < 10 ; $i++) {
          $account = new Account();
          $account->setAmount($this->faker->randomFloat($nbMaxDecimals = 2, $min = -1000, $max = 10000));
          $account->setType($types[array_rand($types)]);
          $account->setOpeningDate(new \DateTime('now'));
          $user = "user" . mt_rand(1,2);
          $account->setUser($this->getReference($user));
          $this->addReference("account$i", $account);
          $manager->persist($account);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
