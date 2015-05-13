<?php

namespace Acme\ReservasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Acme\ReservasBundle\Form\Type\FechaObraType;

class ObraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('foto', 'file')
            ->add('fechasobra', 'collection', array(
	    'type' => new FechaObraType(),
	    'allow_add'    => true,
	    'by_reference' => false,
	    'allow_delete' => true,
            'label'          => '.',
            #'prototype_data' => new FechaObra(),
            #'attr'           => array(
            #    'class' => 'row addresses'
            #    )
	    ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\ReservasBundle\Entity\Obra'
        ));
    }

    public function getName()
    {
        return 'acme_reservasbundle_obra';
    }
}
