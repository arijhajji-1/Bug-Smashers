<?php

namespace App\Form;

use App\Data\SearchDataReparation;
use App\Entity\Reparation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormReparation extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('y',TextType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Chercher Reparation'
                ]
            ])
            ->add('reparation', EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class'=>Reparation::class,
                'expanded'=>true,
                'multiple'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>SearchDataReparation::class,
            'method'=>'GET',
            'csrf_protection'=>false
        ])
        ;
    }

    public function getBlockPrefix()
    {
        return '';
    }
}