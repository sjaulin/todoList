<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskEntityTest extends TestCase
{

    public function testEntityMethods(): void
    {
        $user = new User();
        $user->setUsername('user1')
            ->setPassword('password')
            ->setEmail('user1@mail.com')
            ->setIsVerified(true);

        $task = new Task();
        $task->setTitle('title');
        $task->setContent('content');
        $task->setIsDone(true);
        $task->setAuthor($user);

        $author = $task->getAuthor();
        $this->assertTrue($author === $user);
    }
}
