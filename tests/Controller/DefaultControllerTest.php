<?php

namespace App\Tests\Controller;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testIndex()
    {
        $client = static::createClient();

        // Index
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $content = !empty($content) ? $content : '';
        $this->assertStringContainsString('Bienvenue sur Todo List', $content);
    }
}
