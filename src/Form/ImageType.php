<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => "Nom :",
                'attr' => [
                    'autocomplete' => 'family-name'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom :",
                'attr' => [
                    'autocomplete' => 'given-name'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Choisir un fichier :',
                'constraints' => [
                    new Image()
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
