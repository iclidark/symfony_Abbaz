<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\TaskHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskHistory>
 *
 * @method TaskHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskHistory[]    findAll()
 * @method TaskHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskHistory::class);
    }

    /**
     * @param int $taskId
     * @return TaskHistory[]
     */
    public function getTaskHistory(int $taskId): array
    {
        return $this->createQueryBuilder('th')
            ->andWhere('th.task = :taskId')
            ->setParameter('taskId', $taskId)
            ->orderBy('th.changedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
