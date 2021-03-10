<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Message', 'class' => 'input-avis'],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 10,
                        // max length allowed by Symfony for security reasons
                        'max' => 300,
                    ]),
                ],
            ])
            ->add('nickname', null, [
                'label' => false,
                'attr' => ['placeholder' => "nickname"],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 2,

                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'AccepterLesTermes',
                'constraints' => [
                    new IsTrue()
                ]
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
