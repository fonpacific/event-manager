<?php

namespace App\Components;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('event_search')]
class EventSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private readonly EventRepository $eventRepository
    ) {
    }

    /** @return Event[] */
    public function getEvents(): array
    {
        return $this->eventRepository->search($this->query);
    }
}
