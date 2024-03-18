<?php

declare(strict_types=1);

namespace App\Voter;

use App\Entity\Event;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function assert;

class EventVoter extends Voter
{
    public const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if ($attribute !== self::EDIT) {
            return false;
        }

        return $subject instanceof Event;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        assert($subject instanceof Event);

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            default => throw new LogicException('This code should not be reached!')
        };
    }

    private function canEdit(Event $event, User $user): bool
    {
        return $user === $event->getOrganizer() || $user->hasRole('ROLE_SUPER_ADMIN');
    }
}
