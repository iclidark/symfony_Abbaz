<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create users
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password'));
        $manager->persist($user2);

        // Create a task created 2 days ago
        $task1 = new Task();
        $task1->setName('Recent Task');
        $task1->setDescription('This task was created 2 days ago.');
        $task1->setAuthor($user1);
        $task1->setCreatedAt(new \DateTimeImmutable('-2 days'));
        $manager->persist($task1);

        // Create a task created 10 days ago
        $task2 = new Task();
        $task2->setName('Old Task');
        $task2->setDescription('This task was created 10 days ago.');
        $task2->setAuthor($user2);
        $task2->setCreatedAt(new \DateTimeImmutable('-10 days'));
        $manager->persist($task2);

        // Create a task without a specific creation date (will be set by TimestampableTrait)
        $task3 = new Task();
        $task3->setName('New Task');
        $task3->setDescription('This is a new task.');
        $task3->setAuthor($user1);
        $manager->persist($task3);

        $manager->flush();
    }
}
