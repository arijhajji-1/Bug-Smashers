<?php

namespace App\Form;

use App\Entity\Montage;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;




class MontageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('processeur',EntityType::class,['class' => Product::class,'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 1');},
                'placeholder' => "choisir une option.",
                'choice_label' => function (Product $product){
                     return $product->getNom().' '.$product->getDescription().' '.$product->getPrix();
                }
            ])
            ->add('carte_graphique',EntityType::class,['class' => Product::class,'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 2');},
                'placeholder' => "choisir une option.",
                'choice_label' => function (Product $product){
                    return $product->getNom().' '.$product->getDescription().' '.$product->getPrix();
                }
            ])
            ->add('carte_mere',EntityType::class,['class' => Product::class,'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 3');},
                'placeholder' => "choisir une option.",
                'choice_label' => function (Product $product){
                    return $product->getNom().' '.$product->getDescription().' '.$product->getPrix();
                }
            ])
            ->add('disque_systeme',EntityType::class,['class' => Product::class,'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 4');},
                'placeholder' => "choisir une option.",
                'choice_label' => function (Product $product){
                    return $product->getNom().' '.$product->getDescription().' '.$product->getPrix();
                }
            ])
            ->add('boitier',EntityType::class,['class' => Product::class,'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 5');},
                'placeholder' => "choisir une option.",
                'choice_label' => function (Product $product){
                    return $product->getNom().' '.$product->getDescription().' '.$product->getPrix();
                }
            ])
            ->add('stockage_supp',EntityType::class,['class' => Product::class,'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 6');},
                'placeholder' => "choisir une option.",
                'choice_label' => function (Product $product){
                    return $product->getNom().' '.$product->getDescription().' '.$product->getPrix();
                }
            ])
            ->add('montant',TextareaType::class,[
        'attr' => ['class' => 'tinymce']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Montage::class,
        ]);
    }
}
