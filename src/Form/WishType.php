<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['label'=>'Title:  '])
            ->add('description', TextareaType::class,['label'=>'Description:  '])
            ->add('author', TextType::class,['label'=>'Author:  '])
            ->add('category', EntityType::class,[
                'class'=>Category::class,
                'choice_label'=>'name',
                'query_builder' => function (CategoryRepository $er) {
                return $er->createQueryBuilder('c')->orderBy('c.name');
                }
            ])
            //->add('isPublished')
            //->add('dateCreated',DateType::class,['label'=>'Date Created:  '])
            //->add('dateUpdated',DateType::class,['label'=>'Date Updated:  ', 'required' => false] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
