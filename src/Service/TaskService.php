<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\TaskDTO;
use App\Entity\Project;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findByProject(Project $project): array
    {
        return $this->taskRepository->findBy([
            'project' => $project,
        ]);
    }

    public function save(Task $task): void
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function updateFromDTO(Task $task, TaskDTO $dto): Task
    {
        $dto = array_filter((array)$dto);

        //Quick hack
        foreach ($dto as $key => $value) {
            $method = 'set' . ucfirst($key);
            $task->{$method}($value);
        }

        $this->save($task);

        return $task;
    }
}
