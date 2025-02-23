<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProductVoter extends Voter
{
    public const ADD = 'PRODUCT_ADD';
    public const EDIT = 'PRODUCT_EDIT';
    public const DELETE = 'PRODUCT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::ADD, self::DELETE])        
        && ($subject === null || $subject instanceof \App\Entity\Product);

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
            case self::ADD:
                return $this->canAdd($token);
                
            case self::EDIT:
                return $this->canEdit($token);

            case self::DELETE:
                return $this->canDelete($token);

        }
    }

    protected function canAdd(TokenInterface $token): bool
    {
        $user = $token->getUser();

        return in_array("ROLE_ADMIN", $user->getRoles());       
    }
    
    protected function canEdit(TokenInterface $token): bool
    {
        $user = $token->getUser();

        return in_array("ROLE_ADMIN", $user->getRoles());       
    }

    protected function canDelete(TokenInterface $token): bool
    {
        $user = $token->getUser();
               
        return in_array("ROLE_ADMIN", $user->getRoles());       
    }
}