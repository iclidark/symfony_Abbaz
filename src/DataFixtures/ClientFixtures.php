<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setFirstname("firstname " . $i)
                ->setLastname("lastname " . $i)
                ->setEmail("client" . $i . "@example.com")
                ->setPhoneNumber("06000000" . $i)
                ->setAddress($i . ", rue de la paix")
                ->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($client);
        }

        $manager->flush();
    }
}
