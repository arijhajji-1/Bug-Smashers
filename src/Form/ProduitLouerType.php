<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\ProduitLouer;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitLouerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class'=> 'Label'],
        ])
        ->add('description', TextareaType::class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class'=> 'Label'],
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC');},
            'choice_label' => 'label',
        ])

        ->add('prix', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class'=> 'Label'],
        ])
        ->add('etat', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class'=> 'Label'],
        ])
            ->add('dispo',CheckboxType::class, [
        'label'    => 'Disponible ?',
        'required' => true,
    ])
        ->add('imagePath', FileType::class, [
            'attr' => ['class' => 'file-upload-default'],
            'label' => 'Image De Produit (.png file)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/png'
                    ],
                    'mimeTypesMessage' => 'Please upload a valid .png image',
                ])
            ],
        ])
        ->add('marque', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class'=> 'Label'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitLouer::class,
        ]);
    }
}
