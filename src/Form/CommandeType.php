<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('Telephone')
            ->add('paiment', ChoiceType::class, [
                'choices' => [
                    'Carte bancaire' => 'Carte bancaire',
                    'Cash a la livraison' => 'Cash a la livraison',
                    'Carte E-dinar' => 'Carte E-dinar',

                ],
            ]);

    }


        public
        function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Commande::class,
            ]);

        }


}
