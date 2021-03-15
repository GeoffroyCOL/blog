<?php

namespace App\Test\Repository;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    /**
     * @return void
     */
    public function testCount(): void
    {
        self::bootkernel();
        $reader = self::$container->get(CategoryRepository::class)->count([]);
        $this->assertEquals(10, $reader);
    }
}
