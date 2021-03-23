<?php

namespace App\Tests\Controller;

use App\DataFixtures\InitFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\kernelTestCase;
use App\DataFixtures\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;


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
        $this->assertStringContainsString('Se déconnecter', $client->getResponse()->getContent());
        
        // Logout
        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertStringContainsString('Se connecter', $client->getResponse()->getContent());
        
        // Invalid login
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $client->submitForm('login', ['_username' => 'baduser', '_password' => 'badpassword']);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertStringContainsString('Invalid credentials', $client->getResponse()->getContent());
    }
}
