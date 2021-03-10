<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomPropertyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('refernce')
            ->add('reference')
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event)
    {
        $data = $event->getData();

        if ($data) {
            $data = unserialize($data);
            $event->setData($data);
        }
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if ($data) {
            $data = serialize($data);
            $event->setData($data);
        }
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'    => false,
            'allow_extra_fields' => true
        ]);
    }
}
