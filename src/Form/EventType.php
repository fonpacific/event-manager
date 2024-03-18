<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', CKEditorType::class, [
                'config' => ['toolbar' => 'standard'],
            ])
            ->add('startDate', DateTimeType::class , [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class , [
                'widget' => 'single_text',
            ])
            ->add('maxAttendeesNumber', IntegerType::class, [
                'label' => 'maxinum number of partecipants'
            ])

            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Draft' => 'draft',
                    'Published' => 'published'
                ],
                'placeholder' => 'Select status',
                'required' => false,
            ])
            ->add('registrationsStartDate', DateTimeType::class , [
                'widget' => 'single_text',
            ])
            ->add('registrationsEndDate2', DateTimeType::class , [
                'widget' => 'single_text',
            ])
            ->add('parent')
            ->add('place')
            
            

            ->add('coverImageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_label' => 'download',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
