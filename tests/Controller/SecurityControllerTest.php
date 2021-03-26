<?php

namespace App\Tests\Controller;

use App\DataFixtures\InitFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testLoginLogout()
    {
        $client = static::createClient();

        $this->loadFixtures([
            UserFixtures::class
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

        // Logout
        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $content = $client->getResponse()->getContent();
        $content = !empty($content) ? $content : '';
        $this->assertStringContainsString('Se connecter', $content);

        // Invalid login
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $client->submitForm('login', ['_username' => 'baduser', '_password' => 'badpassword']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $content = $client->getResponse()->getContent();
        $content = !empty($content) ? $content : '';
        $this->assertStringContainsString('Invalid credentials', $content);
    }
}
