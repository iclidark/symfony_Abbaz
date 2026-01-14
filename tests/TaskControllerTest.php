<?php

namespace App\Tests;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskListIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        self::assertResponseIsSuccessful();
    }

    public function testTaskListIsAccessibleWithMock(): void
    {
        $client = static::createClient();

        // Mock the TaskRepository
        $mockTaskRepository = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockTask = (new Task())
            ->setTitle('Mock Task 1')
            ->setDescription('Mock description');
        $reflection = new \ReflectionClass($mockTask);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($mockTask, 123);

        $mockTaskRepository->expects($this->any())
            ->method('findAll')
            ->willReturn([$mockTask]);
        
        static::getContainer()->set('App\Repository\TaskRepository', $mockTaskRepository);

        $client->request('GET', '/tasks');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Mock Task 1');
    }

    public function testTaskShowIsAccessibleWithMock(): void
    {
        $client = static::createClient();

        $mockTask = (new Task())
            ->setTitle('Mock Task')
            ->setDescription('Mock description');
        $reflection = new \ReflectionClass($mockTask);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($mockTask, 123);

        // Mock the TaskRepository
        $mockTaskRepository = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockTaskRepository->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn($mockTask);

        static::getContainer()->set('App\Repository\TaskRepository', $mockTaskRepository);

        $client->request('GET', '/task/123');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Mock Task');
    }
}
