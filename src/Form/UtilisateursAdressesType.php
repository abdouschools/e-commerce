<?php

namespace App\Form;

use App\Entity\UtilisateursAdresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateursAdressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                'label' => false,
                'attr' => ['placeholder' => "Nom"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 2,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('prenom', null, [
                'label' => false,
                'attr' => ['placeholder' => "Prenom"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 2,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('telephone', IntegerType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Phone"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 8,

                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Adresse"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 10,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('cp', IntegerType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Cp"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 5,

                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('pays', null, [
                'label' => false,
                'attr' => ['placeholder' => "Pays"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 5,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('ville', null, [
                'label' => false,
                'attr' => ['placeholder' => "Ville"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 3,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('complement', null, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => "Complement"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 3,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ],
            ]);
        //->add('utilisateur');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UtilisateursAdresses::class,
        ]);
    }
}
