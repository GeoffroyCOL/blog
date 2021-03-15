<?php
// api/src/Security/Voter/BookVoter.php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Reader;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['ACCESS_USER', 'EDIT_USER', 'DELETE_USER']);
        $supportsSubject = $subject instanceof User;

        return $supportsAttribute && $supportsSubject;
    }

    /**
     * @param string $attribute
     * @param User $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        switch ($attribute) {
            case 'ACCESS_USER':
                if ($this->security->isGranted('ROLE_ADMIN') || $subject === $token->getUser()) {
                    return true;
                }
                break;

            case 'EDIT_USER':
                if ($subject === $token->getUser()) {
                    return true;
                }
                break;

            case 'DELETE_USER':
                if ($subject === $token->getUser() && $subject instanceof Reader) {
                    return true;
                }
                break;
        }

        return false;
    }
}
