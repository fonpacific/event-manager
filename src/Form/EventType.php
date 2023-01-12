<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('maxAttendeesNumber', IntegerType::class, [
                'label' => 'Maximum number of participants'
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Draft' => 'draft',
                    'Published' => 'published',
                ],
                'placeholder' => 'Select status',
                'required' => false,
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('registrationStartDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('registrationEndDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('accessStartDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('accessEndDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('parent')
            ->add('place')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
