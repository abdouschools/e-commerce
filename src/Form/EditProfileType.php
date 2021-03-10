<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
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

            ->add('email', EmailType::class, [ //hna hnha zadna type hada bah yafham bali email wyatlob ykon email
                'label' => false,
                'constraints' => [
                    new NotBlank([]),


                    new Length([
                        'min' => 10,

                        // max length allowed by Symfony for security reasons
                        'max' => 100,

                    ]),
                ],
                'attr' => ['placeholder' => "Email"] //wahna golnalo yaktabana f label had laktiba

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
