<?php

namespace App\EntityListener;

use App\Entity\Task;
use App\Entity\TaskHistory;
use Doctrine\ORM\Event\PreRemoveEventArgs;

class TaskListener
{
    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        // The listener is configured only for the Task entity, so this check is not strictly necessary
        // but it's a good practice.
        if (!$entity instanceof Task) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $history = new TaskHistory();
        $history->setTask($entity);
        $history->setAction('deleted');
        $history->setChangedAt(new \DateTimeImmutable());
        
        if ($entity->getAuthor()) {
            $history->setUser($entity->getAuthor());
        }

        $entityManager->persist($history);
        // We need to flush here because the task is about to be deleted,
        // and we want to save the history entry before that happens.
        $entityManager->flush();
    }
}
