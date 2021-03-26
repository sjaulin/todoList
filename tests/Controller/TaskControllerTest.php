<?php

namespace App\Tests\Controller;

use App\DataFixtures\InitFixtures;
use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testCrudTask()
    {
        $client = static::createClient();

        $this->loadFixtures([
            TaskFixtures::class
        ]);

        // Login
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $client->submitForm('login', ['_username' => 'user1', '_password' => 'password']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $content = $client->getResponse()->getContent();
        $content = !empty($content) ? $content : '';
        $this->assertStringContainsString('Se déconnecter', $content);

        // Create Task
        $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Ajouter', [
            'task[title]' => 'task title #1',
            'task[content]' => 'task content'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        // Find Task
        self::bootkernel();
        $task = self::$container->get(TaskRepository::class)->findOneBy([
            'title' => 'task title #1'
        ]);
        $this->assertTrue(!empty($task));
        $task_id = $task->getId();

        // Test Data
        $this->assertNotEmpty($task->getCreatedAt());

        // Edit Task
        $client->request('GET', '/tasks/' . $task_id . '/edit');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Modifier', [
            'task[title]' => 'task title #1-2',
            'task[content]' => 'task content'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        // Find Task
        self::bootkernel();
        $task = self::$container->get(TaskRepository::class)->findOneBy([
            'id' => $task_id,
            'title' => 'task title #1-2'
        ]);
        $this->assertTrue(!empty($task));

        // Toogle task Done
        $client->request('GET', '/tasks/' . $task_id . '/toggle');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertStringContainsString('a bien été marquée comme faite', $client->getResponse()->getContent());
        // Find Task
        self::bootkernel(); //pour éviter pb de cache ?
        $task = self::$container->get(TaskRepository::class)->find($task_id);
        $this->assertTrue($task->getIsDone());

        // Toogle task Not done
        $client->request('GET', '/tasks/' . $task_id . '/toggle');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertStringContainsString('a bien été marquée comme non faite', $client->getResponse()->getContent());
        // Find Task
        self::bootkernel(); //pour éviter pb de cache ?
        $task = self::$container->get(TaskRepository::class)->find($task_id);
        $this->assertFalse($task->getIsDone());

        // Delete Task
        $client->request('GET', '/tasks/' . $task_id . '/delete');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertStringContainsString('a bien été supprimée', $client->getResponse()->getContent());
        // Find Task
        self::bootkernel(); //pour éviter pb de cache ?
        $task = self::$container->get(TaskRepository::class)->find($task_id);
        $this->assertTrue(empty($task));

        // Logout
        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
        $client->followRedirect();

        // Login with another User
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $client->submitForm('login', ['_username' => 'user2', '_password' => 'password']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $content = $client->getResponse()->getContent();
        $content = !empty($content) ? $content : '';
        $this->assertStringContainsString('Se déconnecter', $content);

        // Access Denied because user not author
        $client->request('GET', '/tasks/' . $task_id . '/edit');
        $this->assertResponseStatusCodeSame(404);
        $client->request('GET', '/tasks/' . $task_id . '/delete');
        $this->assertResponseStatusCodeSame(404);
        $client->request('GET', '/tasks/' . $task_id . '/toggle');
        $this->assertResponseStatusCodeSame(404);
    }
}
