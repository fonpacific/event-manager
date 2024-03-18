<?php

namespace App;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Registration;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\UserIsAlreadyRegisteredThisEventException;

class EventManager {

    public function __construct(private EntityManagerInterface $entityManager){

    }

    public function approve(Event $event, bool $endFlush = true): void{
        $event->approve();

        if($endFlush === true) {
            $this->entityManager->flush();
        }
    }

    public function register(Event $event, User $user, bool $andFlush = true): void{
        $registration= $this->findRegistration($event, $user);

        if ($registration !== null) {
            throw new UserIsAlreadyRegisteredThisEventException() ;
        }

        if (!$event->canRegister($user)) {
            return;
        }

        $registration = new Registration($event, $user);
    
        $this->entityManager->persist($registration);
        if ($andFlush === true) {
            $this->entityManager->flush();
        }    
    }

    public function unregister(Event $event, User $user): void{
        $registration= $this->findRegistration($event, $user);

        if ($registration === null) {
            return ;
        }

        $this->entityManager->remove($registration);
        $this->entityManager->flush();
    }

    public function findRegistration(Event $event, User $user): ?Registration {

        return $this->entityManager->getRepository(Registration::class)->findOneBy([
            'platformUser' => $user,
            'event' => $event
        ]);
    }
}