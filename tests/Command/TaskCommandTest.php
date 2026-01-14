<?php

namespace App\Tests\Command;

use App\Command\TaskCommand;
use App\Service\TaskFileService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class TaskCommandTest extends TestCase
{
    private $taskFileService;
    private $application;

    protected function setUp(): void
    {
        // Mock the TaskFileService
        $this->taskFileService = $this->createMock(TaskFileService::class);

        $this->application = new Application();
        $this->application->add(new TaskCommand($this->taskFileService));
    }

    public function testCreateTaskSuccess()
    {
        $this->taskFileService->expects($this->once())
            ->method('createTask')
            ->with('New Task', 'This is a new task.');

        $commandTester = new CommandTester($this->application->find('app:task'));
        $commandTester->execute([
            'action' => 'create',
            '--title' => 'New Task',
            '--description' => 'This is a new task.',
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Task created successfully.', $output);
    }
    
    public function testCreateTaskFailsWithoutOptions()
    {
        $commandTester = new CommandTester($this->application->find('app:task'));
        $commandTester->execute([
            'action' => 'create',
        ]);

        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Title and description are required to create a task.', $output);
    }

    public function testListTasks()
    {
        $this->taskFileService->expects($this->once())
            ->method('listTasks')
            ->willReturn([
                ['id' => 'id1', 'title' => 'Task 1'],
                ['id' => 'id2', 'title' => 'Task 2'],
            ]);

        $commandTester = new CommandTester($this->application->find('app:task'));
        $commandTester->execute(['action' => 'list']);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('id1', $output);
        $this->assertStringContainsString('Task 1', $output);
        $this->assertStringContainsString('id2', $output);
        $this->assertStringContainsString('Task 2', $output);
    }
    
    public function testGetTaskSuccess()
    {
        $this->taskFileService->expects($this->once())
            ->method('getTask')
            ->with('task_id')
            ->willReturn([
                'title' => 'My Task',
                'description' => 'My task description',
                'createdAt' => new \DateTimeImmutable('2023-01-01'),
            ]);

        $commandTester = new CommandTester($this->application->find('app:task'));
        $commandTester->execute([
            'action' => 'get',
            'id' => 'task_id',
        ]);
        
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Title: My Task', $output);
        $this->assertStringContainsString('Description: My task description', $output);
        $this->assertStringContainsString('Created At: 2023-01-01', $output);
    }
    
    public function testUpdateTaskSuccess()
    {
        $this->taskFileService->expects($this->once())
            ->method('updateTask')
            ->with('task_id', 'Updated Title', 'Updated description');

        $commandTester = new CommandTester($this->application->find('app:task'));
        $commandTester->execute([
            'action' => 'update',
            'id' => 'task_id',
            '--title' => 'Updated Title',
            '--description' => 'Updated description'
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Task task_id updated successfully.', $output);
    }
    
    public function testDeleteTaskSuccess()
    {
        $this->taskFileService->expects($this->once())
            ->method('deleteTask')
            ->with('task_id');

        $commandTester = new CommandTester($this->application->find('app:task'));
        $commandTester->execute([
            'action' => 'delete',
            'id' => 'task_id',
        ]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Task task_id deleted successfully.', $output);
    }
}
