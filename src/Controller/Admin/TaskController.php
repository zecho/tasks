<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/projects/{project}/tasks')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'admin_task_index', methods: ['GET'])]
    public function index(Project $project, EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager
            ->getRepository(Task::class)
            ->findAll();

        return $this->render('admin/task/index.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/new', name: 'admin_task_new', methods: ['GET', 'POST'])]
    public function new(Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task($project);
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('admin_task_index', [
                'project' => $project->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/task/new.html.twig', [
            'project' => $project,
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_task_edit', methods: ['GET', 'POST'])]
    public function edit(Project $project, Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_task_index', [
                'project' => $project->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/task/edit.html.twig', [
            'project' => $project,
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_task_delete', methods: ['POST'])]
    public function delete(Project $project, Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_task_index', [
            'project' => $project->getId(),
        ], Response::HTTP_SEE_OTHER);
    }
}
