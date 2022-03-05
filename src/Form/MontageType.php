<?php

namespace App\Form;

use App\Entity\Montage;
use App\Entity\ProduitAcheter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;
use function PHPUnit\Framework\isNull;


class MontageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('processeur', EntityType::class, ['class' => ProduitAcheter::class, 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 1');
            },
                'placeholder' => "choisir une option.",
                'choice_label' => function (ProduitAcheter $ProduitAcheter) {
                    return $ProduitAcheter->getNom() . ' |' . $ProduitAcheter->getDescription() . '| ' . $ProduitAcheter->getPrix();

                },
                'choice_attr' => function (ProduitAcheter $produitAcheter) {
                    return ['data-prix' => $produitAcheter->getPrix()];
                }

            ])
            ->add('carte_graphique', EntityType::class, ['class' => ProduitAcheter::class, 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 2');
            },
                'placeholder' => "choisir une option.",
                'choice_label' => function (ProduitAcheter $ProduitAcheter) {
                    return $ProduitAcheter->getNom() . ' |' . $ProduitAcheter->getDescription() . ' |' . $ProduitAcheter->getPrix();
                },
                'choice_attr' => function (ProduitAcheter $produitAcheter) {
                    return ['data-prix' => $produitAcheter->getPrix()];
                }
            ])
            ->add('carte_mere', EntityType::class, ['class' => ProduitAcheter::class, 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 7');
            },
                'placeholder' => "choisir une option.",
                'choice_label' => function (ProduitAcheter $ProduitAcheter) {
                    return $ProduitAcheter->getNom() . ' |' . $ProduitAcheter->getDescription() . ' |' . $ProduitAcheter->getPrix();
                },
                'choice_attr' => function (ProduitAcheter $produitAcheter) {
                    return ['data-prix' => $produitAcheter->getPrix()];
                }
            ])
            ->add('disque_systeme', EntityType::class, ['class' => ProduitAcheter::class, 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 9');
            },
                'placeholder' => "choisir une option.",
                'choice_label' => function (ProduitAcheter $ProduitAcheter) {
                    return $ProduitAcheter->getNom() . '| ' . $ProduitAcheter->getDescription() . ' |' . $ProduitAcheter->getPrix();
                },
                'choice_attr' => function (ProduitAcheter $produitAcheter) {
                    return ['data-prix' => $produitAcheter->getPrix()];
                }
            ])
            ->add('boitier', EntityType::class, ['class' => ProduitAcheter::class, 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 6');
            },
                'placeholder' => "choisir une option.",
                'choice_label' => function (ProduitAcheter $ProduitAcheter) {
                    return $ProduitAcheter->getNom() . '| ' . $ProduitAcheter->getDescription() . '| ' . $ProduitAcheter->getPrix();
                },
                'choice_attr' => function (ProduitAcheter $produitAcheter) {
                    return ['data-prix' => $produitAcheter->getPrix()];
                }
            ])
            ->add('stockage_supp', EntityType::class, ['class' => ProduitAcheter::class, 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.id', 'ASC')
                    ->where('u.category = 9');
            },
                'placeholder' => "choisir une option.",
                'choice_label' => function (ProduitAcheter $ProduitAcheter) {
                    return $ProduitAcheter->getNom() . ' |' . $ProduitAcheter->getDescription() . '| ' . $ProduitAcheter->getPrix();
                },
                'choice_attr' => function (ProduitAcheter $produitAcheter) {
                    return ['data-prix' => $produitAcheter->getPrix()];
                }
            ])
        ->add ( 'montant',HiddenType::class    )
        ->add ( 'email',HiddenType::class    );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Montage::class,
        ]);
    }
}
