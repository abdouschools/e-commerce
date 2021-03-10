<?php

namespace App\Form;

use App\Entity\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'mapped' => true,
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Mot De Passe Actuelle '
                ],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 6,

                        'max' => 50,
                    ]),
                ],



            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les Mots De Passe Doivent Etre Identique',
                'required' => true,
                'label' => false,
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 6,


                        'max' => 50,

                    ]),
                ],
                'first_options'  => ['attr' => ['placeholder' => "Nouveau Mot De Passe"], 'label' => false],
                'second_options' => ['attr' => ['placeholder' => "Confirmer Le Mot De Passe"], 'label' => false],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class,
        ]);
    }
}
