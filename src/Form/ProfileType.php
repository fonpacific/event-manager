<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class,[
                'required' => false
            ])
            ->add('lastName', TextType::class,[
                'required' => false
            ])
            ->add('avatarImageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_label' => 'download',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'edit']
        ]);
    }
}

