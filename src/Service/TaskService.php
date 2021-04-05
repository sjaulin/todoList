<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class TaskService
{

    public function save(ObjectManager $em, Task $task, User $author = null): void
    {
        if ($author) {
            $task->setAuthor($author);
        }

        $em->persist($task);
        $em->flush();
    }
}
