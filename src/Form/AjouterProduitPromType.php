<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Promotion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterProduitPromType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Promotion',EntityType::class, [
        'class' => Promotion::class,
        'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC');},
        'choice_label' => 'label',
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
