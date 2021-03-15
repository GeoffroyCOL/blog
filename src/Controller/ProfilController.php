<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @return User|null
     */
    public function __invoke(): ?User
    {
        /** @var User $user */
        $user = $this->getUser();

        return $user;
    }
}
