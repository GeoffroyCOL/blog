<?php

namespace App\Tests\Entity;

use App\Entity\Reader;

class ReaderTest extends UserTest
{
    /**
     * @return Reader
     */
    protected function getUser(): Reader
    {
        $reader = new Reader;
        $reader->setUsername('jojojo')
            ->setPassword('Hum123')
            ->setEmail('geoffroy@gmail.com');

        return $reader;
    }
}
