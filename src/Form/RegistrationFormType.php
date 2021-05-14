<?php

namespace App\Form;


use App\Entity\User;
use App\Entity\UtilisateursAdresses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
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

            ])


            ->add('AccepterLesTermes', CheckboxType::class, [
                'mapped' => false,

                'constraints' => [
                    new IsTrue([]),
                ],

            ])
            ->add('plainPassword', RepeatedType::class, [ //hna kanat password type radinaha repeated bah nakriyiw 2 champs ta mod pass wanrado password type hna tahta
                'type' => PasswordType::class,
                'invalid_message' => 'les Mots De Passe Doivent Etre Identique', //hna hationa message derreur
                'required' => true,
                'first_options' => ['attr' => ['placeholder' => "Mot De Passe"],  'label' => false], //hna ki kriyana 2 chamy samana wahda first hna radinaha passsword
                'second_options' =>  ['attr' => ['placeholder' => "Confirmer Le Mot De Passe"],  'label' => false], //wahna radinaha fi 2em password conf...
                'attr' => ['placeholder' => "Password"], //wahna place holder ya3ni ktabna dakhal label 
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 6,

                        // max length allowed by Symfony for security reasons
                        'max' => 50,

                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
