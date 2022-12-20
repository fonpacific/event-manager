<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('maxAttendeesNumber')
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Draft' => 'draft',
                    'Published' => 'published',
                ],
                'placeholder' => 'Select status',
                'required' => false,
            ])
            ->add('startDate')
            ->add('endDate')
            ->add('registrationStartDate')
            ->add('registrationEndDate')
            ->add('accessStartDate')
            ->add('accessEndDate')
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
