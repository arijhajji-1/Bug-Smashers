<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
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
            ->add('idcat', ChoiceType::class, [
                'choices'  => [
                    'Processeur' => 0,
                    'Carte Graphique' => 1,
                    'Casque' => 2,
                ]])
            ->add('prix', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class'=> 'Label'],
            ])
            ->add('qte', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class'=> 'Label'],
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
            'data_class' => Produit::class,
        ]);
    }
}
