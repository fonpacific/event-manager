<?php

declare(strict_types=1);

namespace App\Components;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

use function assert;

#[AsTwigComponent('upcoming_events')]
class UpcomingEventsComponent
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /** @return Event[] */
    public function getUpcomingEvents(): array
    {
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            return [];
        }

        $user = $token->getUser();

        if (! $user instanceof User) {
            return [];
        }

        assert($user instanceof User);

        //return $this->eventRepository->upcomingEventsForUser($user);
        return $this->eventRepository->findBy([], [], 5);
    }
}
