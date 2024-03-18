<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class EventAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            ->add('coverImageName')
            ->add('coverImageSize')
            ->add('coverImageOriginalName')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('description')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            ->add('coverImageName')
            ->add('coverImageSize')
            ->add('coverImageOriginalName')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            ->add('coverImageName')
            ->add('coverImageSize')
            ->add('coverImageOriginalName')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('description')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            ->add('coverImageName')
            ->add('coverImageSize')
            ->add('coverImageOriginalName')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('description')
        ;
    }
}
