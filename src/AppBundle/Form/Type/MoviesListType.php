<?php

// src/AppBundle/Form/Type/MoviesListType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MoviesListType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                '29' => '29',
                '30' => '30',
                '31' => '31',
            )
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}