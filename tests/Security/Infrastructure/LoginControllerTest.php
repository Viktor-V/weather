<?php

declare(strict_types=1);

namespace App\Tests\Security\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = self::createClient();

        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'admin', 'password' => 'admin'])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertNotNull($data['token']);
        self::assertNotNull($data['refreshToken']);
    }

    public function testWrongCredentials(): void
    {
        $client = self::createClient();

        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'wrong', 'password' => 'wrong'])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $data['code']);
        self::assertNotNull('Invalid credentials', $data['message']);
    }
}
