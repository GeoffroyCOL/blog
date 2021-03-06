<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $manager;
    private $repository;
    private $encoder;

    public function __construct(EntityManagerInterface $manager, UserRepository $repository, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->encoder = $encoder;
    }
    
    /**
     * @param  User $user
     * @return User
     */
    public function persist(User $user): User
    {
        if ($user->getPlainPassword()) {
            $user->setPassword(
                $this->encoder->encodePassword($user, $user->getPlainPassword())
            );
            $user->eraseCredentials();
        }

        if (! $user->getId()) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
    
    /**
     * delete
     *
     * @param  User $user
     * @return User
     */
    public function delete(User $user): User
    {
        $this->manager->remove($user);
        $this->manager->flush();

        return $user;
    }

    /**
     * password
     *
     * @param  User $user
     */
    private function password(User $user)
    {
        if ($user->getPlainPassword()) {
            $user->setPassword(
                $this->encoder->encodePassword($user, $user->getPlainPassword())
            );
            $user->eraseCredentials();
        }
    }
}
