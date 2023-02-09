<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    /** @return iterable|array<int,FieldInterface> */
    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name', 'Nome');
        $id = IntegerField::new('id', 'ID');
        $status = ChoiceField::new('status', 'Stato')->setChoices([
            'Bozza' => Event::STATUS_DRAFT,
            'Cancellato' => Event::STATUS_CANCELLED,
            'Pubblicato' => Event::STATUS_PUBLISHED,
        ])->renderAsBadges([
            Event::STATUS_PUBLISHED => 'success',
            Event::STATUS_DRAFT => 'warning',
            Event::STATUS_CANCELLED => 'danger',
        ]);
        $createdAt = DateTimeField::new('createdAt', 'Creazione')->setFormat('dd-MM-YY HH:mm');
        $updatedAt = DateTimeField::new('updatedAt', 'Ultima modifica')->setFormat('dd-MM-YY HH:mm');
        $startDate = DateTimeField::new('startDate', 'Inizio Evento')->setFormat('dd-MM-YY HH:mm');
        $endDate = DateTimeField::new('endDate', 'Fine Evento')->setFormat('dd-MM-YY HH:mm');

        if ($pageName === Crud::PAGE_INDEX) {
            return [$id, $name, $status, $startDate, $endDate];
        }

        if ($pageName === Crud::PAGE_DETAIL) {
            return [$id, $name, $status, $startDate, $endDate, $createdAt, $updatedAt];
        }

        if ($pageName === Crud::PAGE_NEW) {
            return [$name, $status, $startDate, $endDate];
        }

        if ($pageName === Crud::PAGE_EDIT) {
            return [$name, $status, $startDate, $endDate];
        }
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add(ChoiceFilter::new('status')->setChoices([
                    'Bozza' => Event::STATUS_DRAFT,
                    'Cancellato' => Event::STATUS_CANCELLED,
                    'Pubblicato' => Event::STATUS_PUBLISHED,
                ]
            ))
            ->add('startDate')
            ->add('endDate')
            ;
    }

}
