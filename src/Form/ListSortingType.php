<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListSortingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Travel & Adventure' => 'Travel & Adventure',
                    'Sport' => 'Sport',
                    'Entertainment' => 'Entertainment',
                    'Human Relations' => 'Human Relations',
                    'Others' => 'Others'],
                'placeholder' => 'All categories',
                'required' => false,
                'label' => 'Sort by category'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
