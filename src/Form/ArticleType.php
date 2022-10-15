<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre :',
                'attr' => ['placeholder' => 'Titre de la légende']
            ])
            ->add('country', EntityType::class,[
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Pays d\'origine :'
                ])
            ->add('illustration', FileType::class,[
                'label' => 'Image d\'illustration (facultatif) :',
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ])
            ->add('content', TextareaType::class,[
                'label' => 'Contenu :',
                'attr' => ['placeholder' => 'Contenu de la légende']
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Terminer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            "allow_extra_fields" => true
        ]);
    }
}
