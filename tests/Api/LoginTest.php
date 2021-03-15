<?php

namespace App\Tests\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class LoginTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();

        // retrieve a token
        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'jojo81',
                'password' => '0000',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test authorized
        $client->request('GET', '/api/readers', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}
