<?php

namespace App\Service;

class TaskFileService
{
    private array $tasks = [];
    private string $filename;

    public function __construct(string $projectDir)
    {
        $this->filename = $projectDir . '/data/tasks.json';
        if (file_exists($this->filename)) {
            $this->tasks = json_decode(file_get_contents($this->filename), true) ?? [];
        } else {
            $this->tasks = [];
        }
    }

    private function save(): void
    {
        file_put_contents($this->filename, json_encode($this->tasks, JSON_PRETTY_PRINT));
    }

    public function listTasks(): array
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

    public function createTask(array $data): array
    {
        $newTask = [
            'id' => uniqid('task_'),
            'title' => $data['title'],
            'description' => $data['description'],
            'createdAt' => (new \DateTimeImmutable())->format(\DateTimeInterface::ISO8601),
        ];
        $this->tasks[] = $newTask;
        $this->save();
        return $newTask;
    }

    public function updateTask(string $id, array $data): ?array
    {
        foreach ($this->tasks as &$task) {
            if ($task['id'] === $id) {
                if (isset($data['title'])) {
                    $task['title'] = $data['title'];
                }
                if (isset($data['description'])) {
                    $task['description'] = $data['description'];
                }
                $this->save();
                return $task;
            }
        }
        return null;
    }

    public function deleteTask(string $id): bool
    {
        foreach ($this->tasks as $key => $task) {
            if ($task['id'] === $id) {
                unset($this->tasks[$key]);
                $this->save();
                return true;
            }
        }
        return false;
    }
}
