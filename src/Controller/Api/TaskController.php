<?php

namespace App\Controller\Api;

use App\DTO\TaskDTO;
use App\Entity\Project;
use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects/{project}/tasks')]
class TaskController extends AbstractController
{

    public function __construct(
        private readonly TaskService $taskService,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route('', name: 'api_task_index', methods: ['GET'])]
    public function index(Project $project): Response
    {
        $tasks = $this->taskService->findByProject($project);

        return $this->json([
            'data' => $tasks,
        ]);
    }

    #[Route('', name: 'api_task_new', methods: ['POST'])]
    public function create(Project $project, Request $request, ValidatorInterface $validator): Response
    {
        $task = new Task($project);
        $createTaskDTO = $this->serializer->deserialize($request->getContent(), TaskDTO::class, 'json');
        $violations = $validator->validate($createTaskDTO, null, ['Default','create']);

        if (!$violations->count()) {
            try {
                $task = Task::createFromDTO($task, $createTaskDTO);

                $this->taskService->save($task);

                return $this->json([
                    'code' => 0,
                    'data' => $task,
                    'validation_errors' => [],
                ]);
            } catch (\Exception $e) {
                //TODO: Error handling
                throw $e;
            }
        }

        return $this->json([
            'code' => -1,
            'data' => $createTaskDTO,
            'validation_errors' => $violations,
        ]);
    }

    #[Route('/{id}', name: 'api_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->json($task);
    }

    #[Route('/{id}', name: 'api_task_update', methods: ['PUT'])]
    public function update(Request $request, Task $task, ValidatorInterface $validator): Response
    {
        if (!$request->getContent()) {
            throw new BadRequestHttpException('Invalid input');
        }

        $taskDTO = $this->serializer->deserialize($request->getContent(), TaskDTO::class, 'json');
        $violations = $validator->validate($taskDTO, null, ['update']);

        if (!$violations->count()) {
            try {
                $task = $this->taskService->updateFromDTO($task, $taskDTO);

                return $this->json([
                    'code' => 0,
                    'data' => $task,
                    'validation_errors' => [],
                ]);
            } catch (\Exception $e) {
                //TODO: Error handling
                throw $e;
            }
        }

        return $this->json([
            'code' => -1,
            'data' => $taskDTO,
            'validation_errors' => $violations,
        ]);
    }

    #[Route('/{id}', name: 'api_task_delete', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->remove($task);
            $entityManager->flush();
        } catch (\Exception $e) {
            //TODO: Error handling
            throw $e;
        }

        return $this->json(null, 204);
    }
}
