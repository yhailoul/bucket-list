<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', emailType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'enter your email'],
            ])
            ->add('pseudo', TextType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'enter your pseudo'],
            ] )
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'enter password'],
                'constraints' => [
                    new NotBlank(message: 'Please enter a password'),
                    new Length(
                        min: 8,
                        max: 4096,
                        minMessage: 'Your password should be at least 8 characters long'),
                    new Regex(pattern: '/^(?=.*[a-z])/', message: 'Your password must contain at least one lowercase letter'),
                    new Regex(pattern: '/^(?=.*[A-Z])/', message: 'Your password must contain at least one uppercase letter'),
                    new Regex(pattern: '/^(?=.*\d)/', message: 'Your password must contain at least one number'),
                    new Regex(pattern: '/^(?=.*[@$!%*?&])/', message: 'Your password must contain at least one special character (@$!%*?&)'),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
