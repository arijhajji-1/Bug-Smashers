<?php

namespace App\Form;

use App\Entity\Avis_Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', HiddenType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'rating'
                )
            ])
            ->add('description', TextareaType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'Description',
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis_Produit::class,
        ]);
    }
}
