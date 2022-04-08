<?php

namespace App\Form;

use App\Entity\LigneCommande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('STATUS', ChoiceType::class, [
                'choices' => [
                    'Valide'=>'Valide',
                    'annuler' => 'annuler',



                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneCommande::class,
        ]);
    }
}
