<?php
// src/DataFixtures/ClientFixtures.php
namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $client = new Client();
            $client->setName($faker->name);
            $client->setEmail($faker->email);
            $client->setPhoneNumber($faker->phoneNumber);
            $client->setAddress($faker->address);

            $manager->persist($client);
        }

        $manager->flush();
    }
}