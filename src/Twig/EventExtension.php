<?php

namespace App\Twig;

//use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Entity\Event;
use RuntimeException;
use UnhandledMatchError;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\AbstractExtension;


class EventExtension extends AbstractExtension implements ExtensionInterface{


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
        try {
            return match ($status){
                Event::STATUS_PUBLISHED => 'Published',
                Event::STATUS_DRAFT=> 'Draft',
                Event::STATUS_CANCELLED => 'Cancelled',
            };
        } catch (UnhandledMatchError $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

    }

    public function eventStatusClass(string $status): string{
        return match ($status){
            Event::STATUS_PUBLISHED => 'success',
            Event::STATUS_DRAFT=> 'secondary',
            Event::STATUS_CANCELLED => 'danger',
        };
        

    }

}