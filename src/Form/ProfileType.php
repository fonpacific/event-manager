<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Country;
use App\Entity\Province;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

use function assert;
use function count;

class ProfileType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /** @param array<string,mixed> $options */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $provinceRepository = $this->entityManager->getRepository(Province::class);

        $builder
            ->add('firstName', TextType::class, ['required' => false])
            ->add('lastName', TextType::class, ['required' => false])
            ->add('avatarImageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_label' => 'download',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add(
                'country',
                CountryAutocompleteField::class,
            );

        $formModifier = static function (FormInterface $form, ?Country $country = null) use ($provinceRepository): void {
            $provinces = $country === null ? [] : $provinceRepository->findBy(['country' => $country], ['name' => 'ASC']);

            $form->add('province', EntityType::class, [
                'class' => Province::class,
                'placeholder' => '',
                'choices' => $provinces,
                'disabled' => count($provinces) < 1,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            static function (FormEvent $event) use ($formModifier): void {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                assert($data instanceof User);

                $formModifier($event->getForm(), $data->getCountry());
            }
        );

        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            static function (FormEvent $formEvent) use ($formModifier): void {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $country = $formEvent->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback function!
                $formModifier($formEvent->getForm()->getParent(), $country);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'edit'],
        ]);
    }
}
