<?php

namespace App\Command;

use App\Service\TaskFileService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:task',
    description: 'Manages tasks using the TaskFileService.',
)]
class TaskCommand extends Command
{
    public function __construct(private readonly TaskFileService $taskFileService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('action', InputArgument::REQUIRED, 'Action to perform (create, update, list, get, delete)')
            ->addArgument('id', InputArgument::OPTIONAL, 'The ID of the task')
            ->addOption('title', null, InputOption::VALUE_REQUIRED, 'The title of the task')
            ->addOption('description', null, InputOption::VALUE_REQUIRED, 'The description of the task');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');

        switch ($action) {
            case 'create':
                $title = $input->getOption('title');
                $description = $input->getOption('description');
                if (!$title || !$description) {
                    $io->error('Title and description are required to create a task.');
                    return Command::FAILURE;
                }
                $this->taskFileService->createTask(['title' => $title, 'description' => $description]);
                $io->success('Task created successfully.');
                break;

            case 'update':
                $id = $input->getArgument('id');
                $title = $input->getOption('title');
                $description = $input->getOption('description');
                if (!$id || !$title || !$description) {
                    $io->error('ID, title, and description are required to update a task.');
                    return Command::FAILURE;
                }
                try {
                    $this->taskFileService->updateTask($id, ['title' => $title, 'description' => $description]);
                    $io->success("Task {$id} updated successfully.");
                } catch (\Exception $e) {
                    $io->error($e->getMessage());
                    return Command::FAILURE;
                }
                break;

            case 'list':
                $tasks = $this->taskFileService->listTasks();
                $io->table(['ID', 'Title'], array_map(fn($task) => [$task['id'], $task['title']], $tasks));
                break;

            case 'get':
                $id = $input->getArgument('id');
                if (!$id) {
                    $io->error('ID is required to get a task.');
                    return Command::FAILURE;
                }
                try {
                    $task = $this->taskFileService->getTask($id);
                    $createdAt = $task['createdAt'];
                    if ($createdAt instanceof \DateTimeImmutable) {
                        $createdAt = $createdAt->format('Y-m-d H:i:s');
                    }
                    $io->writeln("Title: {$task['title']}");
                    $io->writeln("Description: {$task['description']}");
                    $io->writeln("Created At: {$createdAt}");
                } catch (\Exception $e) {
                    $io->error($e->getMessage());
                    return Command::FAILURE;
                }
                break;

            case 'delete':
                $id = $input->getArgument('id');
                if (!$id) {
                    $io->error('ID is required to delete a task.');
                    return Command::FAILURE;
                }
                try {
                    $this->taskFileService->deleteTask($id);
                    $io->success("Task {$id} deleted successfully.");
                } catch (\Exception $e) {
                    $io->error($e->getMessage());
                    return Command::FAILURE;
                }
                break;

            default:
                $io->error("Invalid action: {$action}");
                return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
