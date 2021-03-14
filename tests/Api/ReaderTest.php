<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ReaderTest extends ApiTestCase
{    
    use RefreshDatabaseTrait;

    /**
     * testGetCollection
     * @dataProvider setDataForMethodGet
     *
     * @return void
     */
    public function testGetCollection(string $route, int $response): void
    {
        $client = static::createClient();
        $client->request('GET', $route);
        $this->assertEquals($response, $client->getResponse()->getStatusCode());
    }
    
    /**
     * setDataForMethodGet
     *
     * @return array
     */
    public function setDataForMethodGet(): array
    {
        return [
            ['/api/readers',    Response::HTTP_OK]
        ];
    }

    /**
     * testCreateReaderWithGoodData
     * Pour l'ajout d'un nouvelle utilisateur de bonnes données
     *
     * @return void
     */
    public function testCreateReaderWithGoodData(): void
    {
        static::createClient()->request('POST', '/api/readers', [
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
            'json' => [
                'username'  => 'new',
                'password'  => '123Hum',
                'email'     => 'reader@domain.fr'
            ]
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
    }
}
