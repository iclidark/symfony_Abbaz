<?php

namespace App\Tests;

use App\Service\TaskFileService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskListIsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Task List');
    }

    public function testTaskListIsAccessibleWithMock()
    {
        $client = static::createClient();

        // Créer un mock pour TaskFileService
        $mockTaskService = $this->createMock(TaskFileService::class);
        $mockTaskService->method('getTasks')->willReturn([
            ['id' => 'mock_id_1', 'title' => 'Mock Task 1', 'description' => 'Description 1', 'createdAt' => new \DateTimeImmutable()],
            ['id' => 'mock_id_2', 'title' => 'Mock Task 2', 'description' => 'Description 2', 'createdAt' => new \DateTimeImmutable()],
        ]);

        // Remplacer le service réel par le mock dans le conteneur de services
        static::getContainer()->set(TaskFileService::class, $mockTaskService);

        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Task List');
        $this->assertSelectorTextContains('body', 'Mock Task 1');
        $this->assertSelectorTextContains('body', 'Mock Task 2');
    }

    public function testTaskShowIsAccessibleWithMock()
    {
        $client = static::createClient();

        $mockTaskService = $this->createMock(TaskFileService::class);
        $mockTaskService->method('getTask')->with('mock_task_123')->willReturn([
            'id' => 'mock_task_123',
            'title' => 'Specific Mock Task',
            'description' => 'A very specific description.',
            'createdAt' => new \DateTimeImmutable(),
        ]);

        static::getContainer()->set(TaskFileService::class, $mockTaskService);

        $client->request('GET', '/task/mock_task_123');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Task Details');
        $this->assertSelectorTextContains('h2', 'Specific Mock Task');
    }
}
