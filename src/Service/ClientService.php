<?php

namespace App\Service;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientService
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function createClient(string $email, string $plainPassword, string $firstname, string $lastname, array $roles = ['ROLE_USER']): Client
    {
        $client = new Client();
        $client->setEmail($email);
        $client->setFirstname($firstname);
        $client->setLastname($lastname);
        $client->setRoles($roles);
        $client->setCreatedAt(new \DateTimeImmutable());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $client,
            $plainPassword
        );
        $client->setPassword($hashedPassword);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}
