<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ENTITY_EDIT', 'ENTITY_DELETE'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
         * @var Task $subject
         */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // If Admin
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // If Author
        switch ($attribute) {
            case 'ENTITY_EDIT':
            case 'ENTITY_DELETE':
                return $subject->getAuthor() === $user;
                break;
        }

        return false;
    }
}
