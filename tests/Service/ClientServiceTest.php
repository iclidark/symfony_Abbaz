<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientServiceTest extends TestCase
{
    private $entityManagerMock;
    private $passwordHasherMock;
    private $clientService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasherMock = $this->createMock(UserPasswordHasherInterface::class);

        $this->clientService = new ClientService($this->entityManagerMock, $this->passwordHasherMock);
    }

    public function testCreateClient(): void
    {
        $email = 'test@example.com';
        $plainPassword = 'password123';
        $firstname = 'Test';
        $lastname = 'Client';

        // Mock the password hasher to return a specific hashed password
        $hashedPassword = 'hashed_password';
        $this->passwordHasherMock->expects($this->once())
            ->method('hashPassword')
            ->willReturn($hashedPassword);

        // Expect that persist and flush will be called on the entity manager
        $this->entityManagerMock->expects($this->once())->method('persist');
        $this->entityManagerMock->expects($this->once())->method('flush');

        $client = $this->clientService->createClient($email, $plainPassword, $firstname, $lastname);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame($email, $client->getEmail());
        $this->assertSame($firstname, $client->getFirstname());
        $this->assertSame($lastname, $client->getLastname());
        $this->assertSame($hashedPassword, $client->getPassword());
        $this->assertContains('ROLE_USER', $client->getRoles());
        $this->assertInstanceOf(\DateTimeImmutable::class, $client->getCreatedAt());
    }
}
