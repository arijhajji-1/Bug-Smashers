<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class, array(
                'attr' => array(
            'placeholder' => 'example@gmail.com')))

            //->add('roles')
            ->add('firstName',TextType::class, array(
                'attr' => array(
            'placeholder' => 'First Name')))
            ->add('lastName',TextType::class, array(
                'attr' => array(
            'placeholder' => 'Last Name')))
            ->add('adresse',TextType::class, array(
                'attr' => array(
            'placeholder' => 'e.g ariana',
                'help' => 'e.g ariana')))
            ->add('photo', FileType::class)
            ->add('telephone',TextType::class, array(
                'attr' => array(
            'placeholder' => 'e.g +216 22 888 555')))
            ->add('cin',TextType::class, array(
                'attr' => array(
            'placeholder' => 'e.g 12345678')))
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('password',PasswordType::class, array(
                'attr' => array(
            'placeholder' => 'Your password')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
