<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Entity\Event;

class EventExtension extends AbstractExtension{


    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array {
        return [
            new TwigFilter('event_status_label', [$this, 'eventStatusLabel']),
            new TwigFilter('event_status_class', [$this, 'eventStatusClass']),
        ];
    }

    public function eventStatuslabel(string $status): string{
        return match ($status){
            Event::STATUS_PUBLISHED => 'Published',
            Event::STATUS_DRAFT=> 'Draft',
            Event::STATUS_CANCELLED => 'Cancelled',
        };
        

    }

    public function eventStatusClass(string $status): string{
        return match ($status){
            Event::STATUS_PUBLISHED => 'success',
            Event::STATUS_DRAFT=> 'secondary',
            Event::STATUS_CANCELLED => 'danger',
        };
        

    }

}