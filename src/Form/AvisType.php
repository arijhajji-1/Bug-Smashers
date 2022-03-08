<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;



class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'Nom'
                )
            ])
            ->add('email', TextType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ])
            ->add('description', TextareaType::class, [
                'label_attr' => ['class' => 'sr-only'],
                'attr' => array(
                    'placeholder' => 'Description',
                )
            ])

            ->add("captchaCode", CaptchaType::class, [
                'captchaConfig' =>'ExampleCaptchaAvis',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again'
                    ])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
