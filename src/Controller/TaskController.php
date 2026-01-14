<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\TaskHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/task/{id}', name: 'task_show')]
    public function show(Task $task, TaskHistoryRepository $taskHistoryRepository): Response
    {
        $history = $taskHistoryRepository->getTaskHistory($task->getId());

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'history' => $history,
        ]);
    }
}
