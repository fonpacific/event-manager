<?php

namespace App\Admin;

use App\Form\ImageType;
use App\Entity\SonataMediaMedia;
use Sonata\MediaBundle\Model\Media;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Entity\BaseMedia;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\Type\CollectionType as TypeCollectionType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\FormTypeInterface;

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
            ->add('parent')
            ->add('children')
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            //->add('image', 'sonata_type_model_list', [], ['link_parameters' =>['context' => 'news']])
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
            ->add('parent')
            ->add('children')
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            ->add('image', ModelListType::class, [
                'class' => SonataMediaMedia::class, // Specifica la classe dell'entità associata
                'required' => false,
                'label' => 'Immagine Evento',
            ])
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('description')
            
            ->add('galleria');

            // ->add('immagine', CollectionType::class, [ // Assuming ImageType handles a collection of images
            //     'entry_type' => ImageType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'required' => false,
            //     'label' => 'Galleria Immagini',
            // ])

        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('parent')
            ->add('children')
            ->add('startDate')
            ->add('endDate')
            ->add('maxAttendeesNumber')
            ->add('status')
            ->add('registrationsStartDate')
            ->add('registrationsEndDate2')
            ->add('coverImageName')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('description')
        ;
    }
}
