<?php
namespace Acme\ReservasBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Acme\ReservasBundle\Entity\Obra;
use Acme\ReservasBundle\Entity\FechaObra;

class ObraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre');
	$builder->add('descripcion');
	$builder->add('foto', 'file');

        $builder->add('fechasobra', 'collection', array(
	    'type' => new FechaObraType(),
	    'allow_add'    => true,
	    'by_reference' => false,
	    'allow_delete' => true,
            'label'          => '.',
	    'prototype_name' => '__fecha__'
            #'prototype_data' => new FechaObra(),
            #'attr'           => array(
            #    'class' => 'row addresses'
            #    )
	    ));
	
	$builder->add('grabar', 'submit', array('label' => 'Grabar los datos'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\ReservasBundle\Entity\Obra',
        ));
    }

    public function getName()
    {
        return 'obra';
    }
}