<?php

namespace App\Tests\Entity;

use App\Entity\Admin;
use App\Repository\AdminRepository;

class AdminTest extends UserTest
{
    /**
     * @return Admin
     */
    protected function getUser(): Admin
    {
        $admin = new Admin;
        $admin->setUsername('jojojo')
            ->setPassword('Hum123')
            ->setEmail('geoffroy@gmail.com');

        return $admin;
    }
}
