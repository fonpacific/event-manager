<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityAutocompleteField(route: 'ux_entity_autocomplete_admin')]
class CountryAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Country::class,
            'placeholder' => 'Choose a Country',
            // 'choice_label' => 'name',
            // 'security' => 'ROLE_SOMETHING',
         
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
