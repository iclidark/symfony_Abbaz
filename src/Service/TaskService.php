<?php

namespace App\Service;

use App\Entity\Task;

class TaskService
{
    public function canEdit(Task $task): bool
    {
        if (null === $task->getCreatedAt()) {
            // Cannot edit a task that has not been persisted yet
            return false;
        }

        $sevenDaysAgo = new \DateTimeImmutable('-7 days');

        return $task->getCreatedAt() > $sevenDaysAgo;
    }
}
