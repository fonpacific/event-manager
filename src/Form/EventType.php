<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description')
            ->add('maxAttendeesNumber', IntegerType::class, [
                'label' => 'Maximum number of participants'
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('registrationStartDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('registrationEndDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('accessStartDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('accessEndDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
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
