<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class OperationFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct()
    {
         $this->faker = Faker\Factory::create();;
    }

    public function load(ObjectManager $manager)
    {
      $types = ["débit", "crédit"];

      for ($i=0; $i < 50; $i++) {
        $operation = new Operation();
        $operation->setAmount($this->faker->randomFloat($nbMaxDecimals = 2, $min = -1000, $max = 1000));
        $operation->setType($types[array_rand($types)]);
        $operation->setRegisteringDate(new \DateTime('now'));
        $operation->setLabel($this->faker->sentence($nbWords = 5, $variableNbWords = true));
        $account = "account" . mt_rand(0, 9);
        $operation->setAccount($this->getReference($account));
        $manager->persist($operation);
      }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AccountFixtures::class,
        );
    }
}
