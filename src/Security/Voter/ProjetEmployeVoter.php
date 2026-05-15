<?php

namespace App\Security\Voter;

use App\Entity\Employe;
use App\Entity\Projet;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjetEmployeVoter extends Voter
{
    public function __construct(private Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'projet.employe' === $attribute && $subject instanceof Projet;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Employe) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Projet $subject */
        return $subject->getEmployes()->contains($user);
    }
}
