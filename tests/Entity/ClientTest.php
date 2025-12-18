<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientEntity()
    {
        $client = new Client();
        $client->setFirstname("John");
        $client->setLastname("Doe");
        $client->setEmail("john.doe@example.com");
        $client->setPhoneNumber("0123456789");
        $client->setAddress("123 Main St");
        $client->setCreatedAt(new \DateTimeImmutable());

        $this->assertEquals("John", $client->getFirstname());
        $this->assertEquals("Doe", $client->getLastname());
        $this->assertEquals("john.doe@example.com", $client->getEmail());
        $this->assertEquals("0123456789", $client->getPhoneNumber());
        $this->assertEquals("123 Main St", $client->getAddress());
        $this->assertInstanceOf(\DateTimeImmutable::class, $client->getCreatedAt());
    }
}
