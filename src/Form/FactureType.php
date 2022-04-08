<?php

namespace App\Form;
use App\Entity\Facture;
use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateFact')
            ->add('remise')
            ->add('total')

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
                    'nom'
                ,
                'label'
                =>
                    'Commande'

    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
