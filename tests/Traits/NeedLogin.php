<?php

namespace App\Tests\Traits;

trait NeedLogin
{
    /**
     * login
     * 
     * @return array
     */
    public function login($client, $user): array
    {
        // retrieve a token
        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $user['username'],
                'password' => $user['password'],
            ],
        ]);

        $json = $response->toArray();
        $this->assertArrayHasKey('token', $json);

        return $json;
    }
}
