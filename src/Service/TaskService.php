<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function create(Task $task): void
    {
        if ($this->tokenStorage->getToken()->getUser()) {
            $task->setAuthor($this->tokenStorage->getToken()->getUser());
        }

        $this->em->persist($task);
        $this->em->flush();
    }

    public function update(Task $task): void
    {
        $this->em->flush();
    }
}
