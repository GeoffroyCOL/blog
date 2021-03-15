<?php

namespace App\Tests\Api;

use App\Tests\Traits\NeedLogin;
use App\Repository\ReaderRepository;
use Symfony\Component\HttpFoundation\Response;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ReaderTest extends ApiTestCase
{
    use RefreshDatabaseTrait, NeedLogin;

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
            ['/api/readers',    Response::HTTP_UNAUTHORIZED],
            ['/api/readers/2',  Response::HTTP_UNAUTHORIZED],
        ];
    }

    /**
     * testRouteNotConnectedWithRoleAdmin
     * @dataProvider setDataRouteForNotConnectedWithRoleAdmin
     *
     * Vérifie si un utilisateur avec un role admin à le droit d'access
     *
     * @return void
     */
    public function testRouteNotConnectedWithRoleAdmin(string $route, int $response): void
    {
        $client = static::createClient();
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);
        $client->request('GET', $route, ['auth_bearer' => $json['token']]);
        $this->assertEquals($response, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataRouteForNotConnectedWithRoleAdmin
     *
     * @return array
     */
    public function setDataRouteForNotConnectedWithRoleAdmin(): array
    {
        return [
            ['/api/readers',    Response::HTTP_OK],
            ['/api/readers/2',  Response::HTTP_OK],
        ];
    }

    /**
     * testRouteNotConnectedWithRoleReader
     * @dataProvider setDataRouteForNotConnectedWithRoleReader
     *
     * Vérifie si un utilisateur avec un role reader à le droit d'access seulement pour cette utilisateur
     *
     * @return void
     */
    public function testRouteNotConnectedWithRoleReader(string $route, int $response): void
    {
        $client = static::createClient();
        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);
        $client->request('GET', $route, ['auth_bearer' => $json['token']]);
        $this->assertEquals($response, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataRouteForNotConnectedWithRoleReader
     *
     * @return array
     */
    public function setDataRouteForNotConnectedWithRoleReader(): array
    {
        return [
            ['/api/readers',    Response::HTTP_FORBIDDEN],
            ['/api/readers/3',  Response::HTTP_FORBIDDEN],
            ['/api/readers/2',  Response::HTTP_OK]
        ];
    }

    /**
     * testRouteConnectedForDeleteProfilByReader
     * 
     * Vérifie si un reader à l'autorisation de supprimer ses informations
     *
     * @return void
     */
    public function testRouteConnectedForDeleteProfilByReader(): void
    {
        $client = static::createClient();

        //Non connecté
        $client->request('DELETE', '/api/readers/3');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);

        //Non autorisé
        $client->request('DELETE', '/api/readers/3', ['auth_bearer' => $json['token']]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());

        //Autorisé
        $client->request('DELETE', '/api/readers/2', ['auth_bearer' => $json['token']]);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        //Vérification de la suppression
        self::bootkernel();
        $reader = self::$container->get(ReaderRepository::class)->find(2);
        $this->assertNull($reader);
    }

    /**
     * testCreateReaderWithGoodData
     * Pour l'ajout d'un nouvelle utilisateur avec de bonnes données
     *
     * @return void
     */
    public function testCreateReaderWithGoodData(): void
    {
        static::createClient()->request('POST', '/api/readers', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username'  => 'new-reader',
                'password'  => '123Hum',
                'email'     => 'reader@domain.fr'
            ]
        ]);
        $this->assertResponseIsSuccessful();
    }

    /**
     * testCreateReaderWithBadData
     * Pour l'ajout d'un nouvelle utilisateur avec de mauvaises données
     *
     * @return void
     */
    public function testCreateReaderWithBadData(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/readers', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username'  => 'new',
                'password'  => '123Hum',
                'email'     => 'reader@domain.fr'
            ]
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
    }
}
