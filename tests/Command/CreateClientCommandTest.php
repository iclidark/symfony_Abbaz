<?php

namespace App\Tests\Command;

use App\Command\CreateClientCommand;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateClientCommandTest extends TestCase
{
    public function testExecute()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Client::class));
        $entityManager->expects($this->once())
            ->method('flush');

        $application = new Application();
        $application->add(new CreateClientCommand($entityManager));

        $command = $application->find('app:create-client');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['John', 'Doe', 'john.doe@example.com', '0123456789', '123 Main St']);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('You have successfully created a new client.', $output);
    }
}
