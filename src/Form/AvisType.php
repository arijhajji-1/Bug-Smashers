<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', TextType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'rating'
                )
            ])
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'Nom'
                )
            ])
            ->add('email', TextType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'Email'
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
            'data_class' => Avis::class,
        ]);
    }
}
