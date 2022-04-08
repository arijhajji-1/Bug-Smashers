<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Location;
use App\Entity\Produit;
use App\Entity\ProduitLouer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDEB')
            ->add('dateFin')
            ->add('TotalL')

        ->add(
        'produit'
        ,EntityType::
    class
        ,[
        'class'
        =>
            ProduitLouer::
            class
        ,
        'choice_label'
        =>
            'nom'
        ,
            'multiple' => true
    ]);}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
