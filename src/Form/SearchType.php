<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Categories;
use App\Entity\Couleurs;
use App\Entity\Formes;
use App\Entity\Marques;
use App\Entity\Styles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Recherche'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Categories::class,
                'expanded' => true,
                'multiple' => true,

            ])
            ->add('couleurs', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Couleurs::class,
                'expanded' => true,
                'multiple' => true,

            ])
            ->add('marques', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Marques::class,
                'expanded' => true,
                'multiple' => true,

            ])
            ->add('formes', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Formes::class,
                'expanded' => true,
                'multiple' => true,

            ])
            ->add('style', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Styles::class,
                'expanded' => true,
                'multiple' => true,

            ])
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix Min'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix Max'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
