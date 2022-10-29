<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class EditNewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre :',
                'attr' => [
                    'placeholder' => 'Titre de la newsletter'
                ]
            ])
            ->add('date', DateType::class,[
                'widget' => 'choice', 
            ])
            ->add('attachment', FileType::class,[
                'label' => 'Pièce jointe : (format : pdf, word ,jpeg, jpg, png)',
                'required' => false,
                'multiple' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'application/pdf',
                            'application/msword',
                        ],
                        'mimeTypesMessage' => 'formats autorisés : pdf, word, jpg, jpeg , png',
                    ])
                ]
            ])
            ->add('content', TextareaType::class,[
                'label' => 'Contenu :',
                'attr' => [
                    'placeholder' => 'Contenu de la newsletter'
                ]
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
