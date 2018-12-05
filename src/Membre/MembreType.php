<?php

namespace App\Membre;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label'  => 'Saisissez votre prénom',
                'attr'   => [
                    'placeholder' => 'Saisissez votre prénom',
                    'class'       => 'form-control'
                ]
            ])
            ->add('nom', TextType::class, [
                'label'  => 'Saisissez votre nom',
                'attr'   => [
                    'placeholder' => 'Saisissez votre nom',
                    'class'       => 'form-control'
                ]
            ])
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
            ->add('conditions', CheckboxType::class, [
                'label'  => 'J\'ai lu et accepte les CGU',
                'attr'   => [
                    'data-toggle' => 'toggle',
                    'data-on'     => 'Oui',
                    'data-off'    => 'Non',
                    'class'       => 'form-check-input'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'  => 'Je m\'inscris',
                'attr'   => [
                    'class'  => 'form-control btn btn-primary'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class
        ]);

    }
}