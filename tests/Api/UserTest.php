<?php

namespace App\Tests\Api;

use App\Tests\Traits\NeedLogin;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    use RefreshDatabaseTrait, NeedLogin;

    /**
     * --------------
     * Test de droits
     * --------------
     */

    /**
     * testRouteNotConnected
     * @dataProvider setDataRouteForNotConnected
     * 
     * Vérifie la réponse si un utilisateur n'est pas connecté
     *
     * @return void
     */
    public function testRouteNotConnected(string $route, int $response): void
    {
        $client = static::createClient();
        $client->request('GET', $route);
        $this->assertEquals($response, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataRouteForNotConnected
     *
     * @return array
     */
    public function setDataRouteForNotConnected(): array
    {
        return [
            ['/api/users', Response::HTTP_UNAUTHORIZED],
            ['/api/users/1', Response::HTTP_UNAUTHORIZED],
            ['/api/users/profil', Response::HTTP_UNAUTHORIZED]
        ];
    }

    /**
     * testRouteConnectedWithRoleAdmin
     * @dataProvider setDataRouteForConnectedWithRoleAdmin
     * 
     * Vérifie si un utilisateur avec un role admin à le droit d'accèss
     *
     * @return void
     */
    public function testRouteConnectedWithRoleAdmin(string $route, int $number): void
    {
        $client = self::createClient();
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);

        $client->request('GET', $route, ['auth_bearer' => $json['token']]);
        $this->assertEquals($number, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataRouteForConnected
     *
     * @return array
     */
    public function setDataRouteForConnectedWithRoleAdmin(): array
    {
        return [
            ['/api/users', Response::HTTP_OK],
            ['/api/users/1', Response::HTTP_OK],
        ];
    }

    /**
     * testRouteConnectedWithRoleReader
     * @dataProvider setDataRouteForConnectedWithRoleReader
     * 
     * Vérifie si un utilisateur avec un role reader n'a pas le droit d'accèss
     *
     * @return void
     */
    public function testRouteConnectedWithRoleReader(string $route, int $number): void
    {
        $client = self::createClient();
        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);

        $client->request('GET', $route, ['auth_bearer' => $json['token']]);
        $this->assertEquals($number, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataRouteForConnectedWithRoleReader
     *
     * @return array
     */
    public function setDataRouteForConnectedWithRoleReader(): array
    {
        return [
            ['/api/users', Response::HTTP_FORBIDDEN],
            ['/api/users/1', Response::HTTP_FORBIDDEN]
        ];
    }

    /**
     * testRouteConnectedProfil
     * @dataProvider setDataRouteForConnectedProfil
     * 
     * Vérifie si un utilisateur avec un role user peur accéder au information de son profil
     *
     * @return void
     */
    public function testRouteConnectedProfil(string $route, int $number): void
    {
        $client = self::createClient();

        //Role admin
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);
        $client->request('GET', $route, ['auth_bearer' => $json['token']]);
        $this->assertEquals($number, $client->getResponse()->getStatusCode());

        //Role reader
        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);
        $client->request('GET', $route, ['auth_bearer' => $json['token']]);
        $this->assertEquals($number, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataRouteForConnectedProfil
     *
     * @return array
     */
    public function setDataRouteForConnectedProfil(): array
    {
        return [
            ['/api/users/profil', Response::HTTP_OK],
        ];
    }

    /**
     * testRouteNotConnected
     * 
     * Vérifie la réponse pour un utilisateur non connecté
     *
     * @return void
     */
    public function testRouteNotConnectedForEditProfil(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/users/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    /**
     * testRouteConnectedForEditProfil
     * 
     * Vérifie si un utilisateur à l'autorisation de modifier ses informations
     *
     * @return void
     */
    public function testRouteConnectedForEditProfil(): void
    {
        $client = static::createClient();

        //user avec l'id 1
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);

        //Autorisation
        $client->request('PUT', '/api/users/1', [
            'auth_bearer' => $json['token'],
            'json' => [
                'email'         => 'email@domain.fr',
                'plainPassword' => 'Hum123'
            ]    
        ]);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        //Non-Autorisé
        $client->request('PUT', '/api/users/2', [
            'auth_bearer' => $json['token'],
            'json' => [
                'email'         => 'email@domain.fr',
                'plainPassword' => 'Hum123'
            ]
        ]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }
}