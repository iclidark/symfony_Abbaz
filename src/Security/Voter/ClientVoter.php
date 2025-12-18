<?php

namespace App\Security\Voter;

use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security; // Correction ici
use Symfony\Component\Security\Core\User\UserInterface;

class ClientVoter extends Voter
{
    public const EDIT = 'CLIENT_EDIT';
    public const VIEW = 'CLIENT_VIEW';

    private $security;

    public function __construct(Security $security) // La déclaration de type utilise maintenant la bonne classe
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Client;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->security->isGranted('ROLE_MANAGER');
            case self::VIEW:
                return $this->security->isGranted('ROLE_MANAGER');
        }

        return false;
    }
}
