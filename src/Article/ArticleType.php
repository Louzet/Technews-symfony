<?php

namespace App\Article;

use App\Entity\Article;
use App\Entity\Categorie;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("titre", TextType::class, [
                "required"  => true,
                "label"     => "Titre de l'article",
                "attr"      => [
                    "placeholder"  => "Titre de l'article"
                ]
            ])
            ->add("categorie", EntityType::class, [
                'class'         => Categorie::class,
                'choice_label'  => 'nom',
                'expanded'      => false,
                'multiple'      => false
            ])
            ->add("content", CKEditorType::class, [
                'required'      => true,
                'label'         => false,
                'attr'          => [
                    'class'     => 'form-control'
                ],
                'config'        => [
                    'toolbar'   => 'standard'
                ]
            ])
            ->add("featuredImage", FileType::class, [
                'required'    => true,
                'label'       => false,
                'attr'        => [
                    'class'   =>"dropify",
                    'data_default_file' => $options['image_url']
                ]
            ])
            ->add("special", CheckboxType::class, [
                'required'    => false,
                'attr'        => [
                    'data-toggle' => 'toggle',
                    'data-on'     => 'Oui',
                    'data-off'    => 'None'
                ]
            ])
            ->add("spotlight", CheckboxType::class, [
                'required'    => false,
                'attr'        => [
                    'data-toggle' => 'toggle',
                    'data-on'     => 'Oui',
                    'data-off'    => 'None'
                ]
            ])
            ->add("Envoyer", SubmitType::class, [
                'label'       => 'Envoyer mon article',
                'attr'       => [
                    'class'  => 'form-control btn btn-success'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        # Permet de dire au formulaire avec quel type d'instance il va travailler
        $resolver->setDefaults([
            'data_class' => Article::class,
            'image_url' => null
        ]);
    }
}