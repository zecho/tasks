<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{

    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findProjects(): array
    {
        return $this->projectRepository->findAll();
    }

    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    public function updateFromDTO(Project $project, ProjectDTO $dto): Project
    {
        $dto = array_filter((array)$dto);

        //Quick hack
        foreach ($dto as $key => $value) {
            $method = 'set' . ucfirst($key);
            $project->{$method}($value);
        }

        $this->save($project);

        return $project;
    }
}
