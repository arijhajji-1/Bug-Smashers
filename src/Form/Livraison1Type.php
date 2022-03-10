<?php

namespace App\Form;
use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Livreur;
use App\Repository\LivreurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Livraison1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('modpaie',ChoiceType::class,
                ['choices'  => [
                    'cash à la livraison' => "cash à la livraison",
                    'par carte' => "par carte",],
                    'expanded'=> true,
                ])
            ->add('modlivr',ChoiceType::class,
                ['choices'  => [
                    'à domicile' => "à domicile",
                    'dans un point relai' => "dans un point relai",],
                    'expanded'=> true,
                ])
            ->add('region' , ChoiceType::class,
                ['choices'  => [
                    'ariana' => "ariana",
                    'tunis' => "tunis",],
                    'expanded'=> true,
                ])

            ->add('description')
            ->add('date')
            ->add(
                'Commande'
                ,EntityType::
            class
                ,['class'
                => Commande::class,
                'choice_label' => 'prenom',
                'label' => 'Commande'
            ])

        ;}



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
