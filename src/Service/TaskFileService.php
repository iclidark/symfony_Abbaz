<?php

namespace App\Service;

class TaskFileService
{
    private $tasks = [];
    private $filename;

    // Pour la simplicité, nous allons coder en dur le chemin du fichier.
    // Dans une vraie application, cela viendrait d'un paramètre de configuration.
    public function __construct(string $projectDir)
    {
        $this->filename = $projectDir . '/data/tasks.json';
        if (file_exists($this->filename)) {
            $this->tasks = json_decode(file_get_contents($this->filename), true);
        } else {
            // Créer des données par défaut si le fichier n'existe pas
            $this->tasks = [
                ['id' => 'task_1', 'title' => 'Real Task 1', 'description' => 'Description for real task 1', 'createdAt' => new \DateTimeImmutable()],
                ['id' => 'task_2', 'title' => 'Real Task 2', 'description' => 'Description for real task 2', 'createdAt' => new \DateTimeImmutable()],
            ];
        }
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function getTask(string $id): ?array
    {
        foreach ($this->tasks as $task) {
            if ($task['id'] === $id) {
                return $task;
            }
        }
        return null;
    }
}
