<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Service\UserService;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        $user = $this->userService->persist($data);
        return $user;
    }

    public function remove($data, array $context = [])
    {
        $user = $this->userService->delete($data);
        return $user;
    }
}
