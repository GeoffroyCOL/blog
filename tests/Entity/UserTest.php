<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Reader;
use App\Repository\UserRepository;
use App\Tests\Traits\AssertHasErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class UserTest extends KernelTestCase
{
    use AssertHasErrors;
    
    /**
     * @return User
     */
    abstract protected function getUser();

    /**
     * testValideUser
     * Test si l'admin est valide
     *
     * @return void
     */
    public function testValideUser(): void
    {
        $this->assertHasErrors($this->getUser(), 0);
    }
    
    /**
     * testUsernameNotBlank
     * Si la propriété username est vide
     *
     * @return void
     */
    public function testUsernameNotBlank()
    {
        $user = $this->getUser();
        $user->setUsername('');

        $this->assertHasErrors($user, 2);
    }

    /**
     * testUsernameLength
     * Si la propriété username possède 6 caractères
     *
     * @return void
     */
    public function testUsernameLength(): void
    {
        $user = $this->getUser();
        $user->setUsername('jojo');

        $this->assertHasErrors($user, 1);
    }
    
    /**
     * testUsernameUnique
     * Si l'utilisateur est unqiue
     *
     * @return void
     */
    public function testUsernameUnique(): void
    {
        $user = $this->getUser();

        if ($user instanceof Admin) {
            $user->setUsername('jojo81');
        }

        if ($user instanceof Reader) {
            $user->setUsername('reader81');
        }

        self::bootkernel();
        self::$container->get(UserRepository::class);

        $this->assertHasErrors($user, 1);
    }

    /**
     * testPasswordNotBlank
     * Si la propriété password est vide
     *
     * @return void
     */
    public function testPasswordNotBlank(): void
    {
        $user = $this->getUser();
        $user->setPassword('');

        $this->assertHasErrors($user, 1);
    }

    /**
     * testPasswordBadFormat
     * Si la propriété password est au bon format
     *
     * @return void
     */
    public function testPasswordBadFormat(): void
    {
        $user = $this->getUser();
        $user->setPassword('123');

        $this->assertHasErrors($user, 1);
    }

    /**
     * testEmailNotBlank
     * Si la propriété email est vide
     *
     * @return void
     */
    public function testEmailNotBlank()
    {
        $user = $this->getUser();
        $user->setEmail('');

        $this->assertHasErrors($user, 1);
    }

    /**
     * testEmailBadFormat
     * Si la propriété email est vide
     *
     * @return void
     */
    public function testEmailBadFormat()
    {
        $user = $this->getUser();
        $user->setEmail('thrhrthtr');

        $this->assertHasErrors($user, 1);
    }
}
