<?php

declare(strict_types=1);

namespace App;

use App\Entity\Event;
use App\Entity\Registration;
use App\Entity\User;
use App\Exception\UserIsAlreadyRegisteredToThisEventException;
use Doctrine\ORM\EntityManagerInterface;

class EventManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function approve(Event $event, bool $andFlush = true): void
    {
        $event->approve();

        if ($andFlush !== true) {
            return;
        }

        $this->entityManager->flush();
    }

    /** @throws UserIsAlreadyRegisteredToThisEventException */
    public function register(Event $event, User $user, bool $andFlush = true): void
    {
        $registration = $this->findRegistration($event, $user);

        if ($registration !== null) {
            throw new UserIsAlreadyRegisteredToThisEventException();
        }

        if (! $event->canRegister($user)) {
            return;
        }

        $registration = new Registration($event, $user);

        $this->entityManager->persist($registration);
        if ($andFlush !== true) {
            return;
        }

        $this->entityManager->flush();
    }

    public function unregister(Event $event, User $user): void
    {
        $registration = $this->findRegistration($event, $user);

        if ($registration === null) {
            return;
        }

        $this->entityManager->remove($registration);
        $this->entityManager->flush();
    }

    private function findRegistration(Event $event, User $user): ?Registration
    {
        return $this->entityManager->getRepository(Registration::class)->findOneBy([
            'platformUser' => $user,
            'event' => $event,
        ]);
    }
}
