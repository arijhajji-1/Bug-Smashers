<?php

namespace App\Form;

use App\Entity\LigneCommande;
use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LigneCommande1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('STATUS', ChoiceType::class, [
                'choices' => [
                    'Valide'=>'Valide',
                    'annuler' => 'annuler',
                    'LIVRAISON EN COURS'=>'LIVRAISON EN COURS',



                ],
            ])

          ->add(
        'Commande'
        ,EntityType::
    class
        ,[
        'class'
        =>
            Commande::
            class
        ,
        'choice_label'
        =>
            'prenom'
        ,
        'label'
        =>
            'Commande'

    ]);}




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneCommande::class,
        ]);
    }
}
