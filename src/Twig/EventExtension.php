<?php

namespace App\Twig;

use App\Entity\Event;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class EventExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('event_status_label', [$this, 'eventStatusLabel']),
            new TwigFilter('event_status_class', [$this, 'eventStatusClass']),
        ];
    }

    public function eventStatusLabel(string $status): string
    {
        return match ($status) {
            Event::STATUS_PUBLISHED => 'Published',
            Event::STATUS_DRAFT => 'Draft',
            Event::STATUS_CANCELLED => 'Cancelled',
        };
    }
    public function eventStatusClass(string $status): string
    {
        return match ($status) {
            Event::STATUS_PUBLISHED => 'success',
            Event::STATUS_DRAFT => 'secondary',
            Event::STATUS_CANCELLED => 'danger',
        };
    }
}