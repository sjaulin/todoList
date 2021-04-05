<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Exception;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 *
 * @Route("/tasks")
 *
 * @IsGranted("ROLE_USER")
 */
class TaskController extends AbstractController
{

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/", name="task_list")
     */
    public function listAction(): Response
    {
        return $this->render(
            'task/list.html.twig',
            [
                'tasks' => $this->taskRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/done", name="task_list_done")
     */
    public function listActionDone(): Response
    {
        return $this->render(
            'task/list.html.twig',
            [
                'tasks' => $this->taskRepository->findDone(),
            ]
        );
    }

    /**
     * @Route("/create", name="task_create")
     */
    public function createAction(Request $request, TaskService $taskService): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->save($this->getDoctrine()->getManager(), $task, $this->getUser());
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request, TaskService $taskService): Response
    {

        if (!$this->isGranted('ENTITY_EDIT', $task)) {
            throw new AccessDeniedHttpException("Vous ne pouvez pas modifier cette tâche");
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->save($this->getDoctrine()->getManager(), $task, null);
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task): Response
    {
        if (!$this->isGranted('ENTITY_EDIT', $task)) {
            throw new AccessDeniedHttpException("Vous ne pouvez pas modifier cette tâche");
        }

        $task->setIsDone(!$task->getIsDone());
        $this->getDoctrine()->getManager()->flush();

        if ($task->getIsDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task): Response
    {

        if (!$this->isGranted('ENTITY_EDIT', $task)) {
            throw new AccessDeniedHttpException("Vous ne pouvez pas modifier cette tâche");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
