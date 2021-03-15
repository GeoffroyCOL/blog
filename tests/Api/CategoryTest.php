<?php

namespace App\Tests\Api;

use App\Tests\Traits\NeedLogin;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CategoryTest extends ApiTestCase
{
    use RefreshDatabaseTrait, NeedLogin;
    
    /**
     * testRouteMethodPost
     * Vérifie si la route n'est accessible que par un user avec un ROLE_ADMIN
     *
     * @return void
     */
    public function testRouteMethodPost(): void
    {
        $client = static::createClient();

        //Non connecté
        $client->request('POST', '/api/categories');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        //Connecté en tant que reader
        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);

        $client->request('POST', '/api/categories', ['auth_bearer' => $json['token']]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }
    
    /**
     * testAddCategoryWithBadData
     * Test l'ajout d'une catégorie avec de mauvaises données
     *
     * @return void
     */
    public function testAddCategoryWithBadData(): void
    {
        $client = static::createClient();
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);
        $client->request('POST', '/api/categories', [
            'auth_bearer' => $json['token'],
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'name'  => 'ne',
            ]
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
    }

    /**
     * testAddCategoryWithGoodData
     * Test l'ajout d'une catégorie avec de bonnes données
     *
     * @return void
     */
    public function testAddCategoryWithGoodData(): void
    {
        $client = static::createClient();
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);

        static::createClient()->request('POST', '/api/categories', [
            'auth_bearer' => $json['token'],
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'name'  => 'nouvelle catégorie'
            ]
        ]);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * testRouteMethodPut
     * Vérifie si la route n'est accessible que par un user avec un ROLE_ADMIN
     *
     * @return void
     */
    public function testRouteMethodPut(): void
    {
        $client = static::createClient();

        //Non connecté
        $client->request('PUT', '/api/categories/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        //Connecté en tant que reader
        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);

        $client->request('PUT', '/api/categories/1', ['auth_bearer' => $json['token']]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * testEditCategory
     * Test la modification d'une catégorie
     *
     * @return void
     */
    public function testEditCategory(): void
    {
        $client = static::createClient();
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);
        $client->request('PUT', '/api/categories/1', [
            'auth_bearer' => $json['token'],
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'name'  => 'new category',
            ]
        ]);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * testRouteMethodDelete
     * Vérifie si la route n'est accessible que par un user avec un ROLE_ADMIN
     *
     * @return void
     */
    public function testRouteMethodDelete(): void
    {
        $client = static::createClient();

        //Non connecté
        $client->request('DELETE', '/api/categories/1');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        //Connecté en tant que reader
        $json = $this->login($client, ['username' => 'reader81', 'password' => '0000']);

        $client->request('DELETE', '/api/categories/1', ['auth_bearer' => $json['token']]);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * testDeleteCategory
     * Test la suppression d'une catégorie
     *
     * @return void
     */
    public function testDeleteCategory(): void
    {
        $client = static::createClient();
        $json = $this->login($client, ['username' => 'jojo81', 'password' => '0000']);
        $client->request('DELETE', '/api/categories/1', [
            'auth_bearer' => $json['token'],
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'name'  => 'new category',
            ]
        ]);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        //Vérification de la suppression
        self::bootkernel();
        $category = self::$container->get(CategoryRepository::class)->find(1);
        $this->assertNull($category);
    }
}
