<?php

namespace App\Controller\Api;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/projects')]
class ProjectController extends AbstractController
{

    public function __construct(
        private readonly ProjectService $projectService,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route('', name: 'api_project_index', methods: ['GET'])]
    public function index(): Response
    {
        $projects = $this->projectService->findProjects();

        return $this->json([
            'data' => $projects,
        ]);
    }

    #[Route('', name: 'api_project_new', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $projectDTO = $this->serializer->deserialize($request->getContent(), ProjectDTO::class, 'json');
        $violations = $validator->validate($projectDTO, null, ['Default', 'create']);

        if (!$violations->count()) {
            try {
                $project = Project::createFromDTO($projectDTO);

                $this->projectService->save($project);

                return $this->json([
                    'code' => 0,
                    'data' => $project,
                    'validation_errors' => [],
                ]);
            } catch (\Exception $e) {
                //TODO: Error handling
                throw $e;
            }
        }

        return $this->json([
            'code' => -1,
            'data' => $projectDTO,
            'validation_errors' => $violations,
        ]);
    }

    #[Route('/{id}', name: 'api_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->json($project);
    }

    #[Route('/{id}', name: 'api_project_update', methods: ['PUT'])]
    public function update(Request $request, Project $project, ValidatorInterface $validator): Response
    {
        if (!$request->getContent()) {
            throw new BadRequestHttpException('Invalid input');
        }

        $projectDTO = $this->serializer->deserialize($request->getContent(), ProjectDTO::class, 'json');
        $violations = $validator->validate($projectDTO, null, ['Default', 'update']);

        if (!$violations->count()) {
            try {
                $this->projectService->updateFromDTO($project, $projectDTO);

                return $this->json([
                    'code' => 0,
                    'data' => $project,
                    'validation_errors' => [],
                ]);
            } catch (\Exception $e) {
                //TODO: Error handling
                throw $e;
            }
        }

        return $this->json([
            'code' => -1,
            'data' => $projectDTO,
            'validation_errors' => $violations,
        ]);
    }

    #[Route('/{id}', name: 'api_project_delete', methods: ['DELETE'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_project_index', [], Response::HTTP_SEE_OTHER);
    }
}
