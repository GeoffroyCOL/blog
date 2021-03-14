<?php

namespace App\Test\Repository;

use App\Repository\ReaderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReaderRepositoryTest extends KernelTestCase
{
    /**
     * @return void
     */
    public function testCount(): void
    {
        self::bootkernel();
        $admin = self::$container->get(ReaderRepository::class)->count([]);
        $this->assertEquals(5, $admin);
    }
}
