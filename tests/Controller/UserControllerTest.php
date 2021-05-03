<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\DataFixtures\InitFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testCrudUser(): void
    {
        $client = static::createClient();

        $this->loadFixtures([
            UserFixtures::class
        ]);

        // Login
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Se connecter', ['_username' => 'admin', '_password' => 'password']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $content = $client->getResponse()->getContent();
        $content = !empty($content) ? $content : '';
        $this->assertStringContainsString('Se déconnecter', $content);

        // Create User
        $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Ajouter', [
            'user[username]' => 'newuser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'newuser@mail.com'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        // Find User
        self::bootkernel();
        $container = self::$container;
        $user = $container->get(UserRepository::class)->findOneBy([
            'username' => 'newuser'
        ]);
        $this->assertTrue(!empty($user));
        $user_id = $user->getId();

        // List Users
        $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();

        // Edit User
        $client->request('GET', '/users/' . $user_id . '/edit');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Modifier', [
            'user[username]' => 'newuser2',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'newuser@mail.com'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();

        // Find User
        self::bootkernel();
        $container = self::$container;
        $user = $container->get(UserRepository::class)->findOneBy([
            'id' => $user_id,
            'username' => 'newuser2'
        ]);
        $this->assertTrue(!empty($user));
    }

    public function testAccessDeniedNonAdmin(): void
    {
        $client = static::createClient();

        $this->loadFixtures([
            UserFixtures::class
        ]);

        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Se connecter', ['_username' => 'user1', '_password' => 'password']);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(403);

        $client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(403);

        // Find User
        self::bootkernel();
        $users = self::$container->get(UserRepository::class)->findAll();
        $user = $users[0];
        $this->assertTrue($user instanceof User);

        // Access Denied because none admin
        $client->request('GET', '/users/' . $user->getId() . '/edit');
        $this->assertResponseStatusCodeSame(403);
    }
}
