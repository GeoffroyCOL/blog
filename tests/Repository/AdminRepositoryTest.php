<?php

namespace App\Test\Repository;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminRepositoryTest extends KernelTestCase
{
    /**
     * @return void
     */
    public function testCount(): void
    {
        self::bootkernel();
        $admin = self::$container->get(AdminRepository::class)->count([]);
        $this->assertEquals(1, $admin);
    }
}
