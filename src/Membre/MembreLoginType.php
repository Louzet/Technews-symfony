<?php

namespace App\Membre;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreLoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label'  => 'Saisissez votre email',
                'attr'   => [
                    'placeholder' => 'Saisissez votre email',
                    'class'       => 'form-control'
                ]
            ])

            ->add('password', PasswordType::class, [
                'label'  => 'Saisissez votre mot de passe',
                'attr'   => [
                    'placeholder' => 'Saisissez votre mot de passe',
                    'class'       => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'  => 'Connexion',
                'attr'   => [
                    'class'  => 'btn btn-primary'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);

    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'app_login';
    }
}