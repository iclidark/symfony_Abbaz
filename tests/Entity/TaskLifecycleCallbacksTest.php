<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\TaskHistory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskLifecycleCallbacksTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        // Start a transaction to isolate tests
        $this->entityManager->beginTransaction();
    }

    public function testTaskCreationLogsHistory(): void
    {
        $user = new User();
        $user->setEmail('creation_test@example.com');
        $user->setPassword('password');
        $user->setFirstname('Test');
        $user->setLastname('User');
        $this->entityManager->persist($user);

        $task = new Task();
        $task->setTitle('Test Task');
        $task->setAuthor($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->assertCount(1, $task->getHistory(), 'A history entry should be created on persist.');
        $history = $task->getHistory()->first();
        $this->assertEquals('created', $history->getAction());
        $this->assertEquals($user, $history->getUser());
    }

    public function testTaskUpdateLogsHistory(): void
    {
        $user = new User();
        $user->setEmail('update_test@example.com');
        $user->setPassword('password');
        $user->setFirstname('Update');
        $user->setLastname('User');
        $this->entityManager->persist($user);

        $task = new Task();
        $task->setTitle('Original Title');
        $task->setAuthor($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        // Update the task
        $task->setTitle('Updated Title');
        $this->entityManager->flush();

        $this->assertCount(2, $task->getHistory(), 'A second history entry should be created on update.');
        // The most recent history entry is at the end of the collection.
        $updateHistory = $task->getHistory()->last(); 
        $this->assertEquals('updated', $updateHistory->getAction());
    }

    public function testTaskRemovalLogsHistory(): void
    {
        $user = new User();
        $user->setEmail('remove_test@example.com');
        $user->setPassword('password');
        $user->setFirstname('Remove');
        $user->setLastname('User');
        $this->entityManager->persist($user);

        $task = new Task();
        $task->setTitle('Task to be removed');
        $task->setAuthor($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        
        // Remove the task
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        // The Task entity is gone, but the history should remain.
        // We can find the history by looking for entries associated with the user.
        $historyRepo = $this->entityManager->getRepository(TaskHistory::class);
        $historyEntries = $historyRepo->findBy(['user' => $user], ['changedAt' => 'ASC']);
        
        $this->assertCount(2, $historyEntries, 'There should be 2 history entries: created and deleted.');
        $actions = array_map(fn($h) => $h->getAction(), $historyEntries);
        $this->assertEquals('created', $actions[0]);
        $this->assertEquals('deleted', $actions[1]);
    }

    protected function tearDown(): void
    {
        // Roll back the transaction to undo any changes
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
