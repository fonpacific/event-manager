<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\EventManager;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EventCrudController extends AbstractCrudController
{
    public function __construct(private EventManager $eventManager)
    {

    }

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->showEntityActionsInlined(true);
        $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
        return $crud;
    }

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
        $description = TextareaField::new('description', 'Descrizione')->setFormType(CKEditorType::class)
        ->setFormTypeOption('config', array('toolbar' => 'standard'));
        $createdAt = DateTimeField::new('createdAt', 'Creazione')->setFormat('dd-MM-YY HH:mm');
        $updatedAt = DateTimeField::new('updatedAt', 'Ultima modifica')->setFormat('dd-MM-YY HH:mm');
        $startDate = DateTimeField::new('startDate', 'Inizio Evento')->setFormat('dd-MM-YY HH:mm');
        $endDate = DateTimeField::new('endDate', 'Fine Evento')->setFormat('dd-MM-YY HH:mm');

        if ($pageName === Crud::PAGE_INDEX) {
            return [$id, $name, $status, $startDate, $endDate];
        }

        if ($pageName === Crud::PAGE_DETAIL) {
            return [$id, $name, $status, $startDate, $endDate,$description, $createdAt, $updatedAt];
        }

        if ($pageName === Crud::PAGE_NEW) {
            return [$name, $status, $startDate, $endDate, $description];
        }

        if ($pageName === Crud::PAGE_EDIT) {
            return [$name, $status, $startDate, $endDate, $description];
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        $approveEvent = Action::new('approveEvent', 'Approva')
            ->displayIf(static function ($entity) {
                return $entity->getStatus() === Event::STATUS_DRAFT;
            })
            ->linkToCrudAction('approveEvent')
            ->setHtmlAttributes(['title' => 'Approva Evento']);

        return $actions
            ->add(Crud::PAGE_INDEX, $approveEvent);
    }

    public function approveEvent(AdminContext $context): RedirectResponse
    {
        $event = $context->getEntity()->getInstance();

        $this->eventManager->approve($event);

       $url = empty($context->getReferrer())
           ? $this->container->get(AdminUrlGenerator::class)->setAction(Action::INDEX)->generateUrl()
           : $context->getReferrer();

       return $this->redirect($url);
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
