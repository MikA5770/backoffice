<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ClientVoter extends Voter
{
    public const VIEW = 'CLIENT_VIEW';
    public const ADD = 'CLIENT_ADD';
    public const EDIT = 'CLIENT_EDIT';
    public const DELETE = 'CLIENT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::ADD, self::EDIT, self::DELETE])
        && ($subject === null || $subject instanceof \App\Entity\Client);

    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                return count(array_intersect(["ROLE_ADMIN", "ROLE_MANAGER"], $user->getRoles())) > 0;
                break;
            case self::ADD:
                return count(array_intersect(["ROLE_ADMIN", "ROLE_MANAGER"], $user->getRoles())) > 0;
                break;
            case self::EDIT:
                return count(array_intersect(["ROLE_ADMIN", "ROLE_MANAGER"], $user->getRoles())) > 0;
                break;
            case self::DELETE:
                return count(array_intersect(["ROLE_ADMIN", "ROLE_MANAGER"], $user->getRoles())) > 0;
                break;
        }
    }
}