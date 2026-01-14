<?php

namespace App\Tests\Command;

use App\Command\TaskCommand;
use App\Service\TaskFileService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class TaskCommandTest extends KernelTestCase
{
    private MockObject|TaskFileService $taskServiceMock;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->taskServiceMock = $this->createMock(TaskFileService::class);
        self::getContainer()->set(TaskFileService::class, $this->taskServiceMock);

        $command = self::getContainer()->get(TaskCommand::class);
        $this->commandTester = new CommandTester($command);
    }

    public function testCreateTaskSuccess(): void
    {
        $this->taskServiceMock->expects($this->once())
            ->method('createTask')
            ->with(['title' => 'New Task', 'description' => 'New Description']);

        $this->commandTester->execute([
            'action' => 'create',
            '--title' => 'New Task',
            '--description' => 'New Description',
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Task created successfully.', $output);
    }

    public function testCreateTaskFailure(): void
    {
        $this->commandTester->execute([
            'action' => 'create',
        ]);

        $this->assertEquals(1, $this->commandTester->getStatusCode());
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Title and description are required to create a task.', $output);
    }

    public function testListTasks(): void
    {
        $this->taskServiceMock->method('listTasks')->willReturn([
            ['id' => 'task_1', 'title' => 'Task 1'],
            ['id' => 'task_2', 'title' => 'Task 2'],
        ]);

        $this->commandTester->execute(['action' => 'list']);

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Task 1', $output);
        $this->assertStringContainsString('Task 2', $output);
    }

    public function testGetTaskSuccess(): void
    {
        $task = [
            'id' => 'task_1',
            'title' => 'Test Task',
            'description' => 'Test Description',
            'createdAt' => new \DateTimeImmutable(),
        ];
        $this->taskServiceMock->method('getTask')->willReturn($task);

        $this->commandTester->execute([
            'action' => 'get',
            'id' => 'task_1',
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Test Task', $output);
        $this->assertStringContainsString('Test Description', $output);
        $this->assertStringContainsString($task['createdAt']->format('Y-m-d H:i:s'), $output);
    }

    public function testUpdateTaskSuccess(): void
    {
        $this->taskServiceMock->expects($this->once())
            ->method('updateTask')
            ->with('task_1', ['title' => 'Updated Title', 'description' => 'Updated Description']);

        $this->commandTester->execute([
            'action' => 'update',
            'id' => 'task_1',
            '--title' => 'Updated Title',
            '--description' => 'Updated Description',
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Task task_1 updated successfully.', $output);
    }

    public function testDeleteTaskSuccess(): void
    {
        $this->taskServiceMock->expects($this->once())
            ->method('deleteTask')
            ->with('task_1');

        $this->commandTester->execute([
            'action' => 'delete',
            'id' => 'task_1',
        ]);

        $this->commandTester->assertCommandIsSuccessful();
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Task task_1 deleted successfully.', $output);
    }
}
