<?php

declare(strict_types=1);

namespace App\Tests\Security\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RefreshTokenControllerTest extends WebTestCase
{
    public function testRefreshToken(): void
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

        $loginData = json_decode($client->getResponse()->getContent(), true);

        self::assertNotNull($loginData['token']);
        self::assertNotNull($loginData['refreshToken']);

        $client->request(
            'POST',
            '/api/login/refresh',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['refreshToken' => $loginData['refreshToken']])
        );

        $refreshData = json_decode($client->getResponse()->getContent(), true);

        self::assertNotNull($refreshData['token']);
        self::assertNotNull($refreshData['refreshToken']);
    }
}
