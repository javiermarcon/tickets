<?php
namespace Acme\ReservasBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Acme\ReservasBundle\Entity\HorarioObra;

class FechaObraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fecha');
        #$builder->add('horariosobra', 'collection', array(
	#    'type' => new HorarioObraType(),
	#    'allow_add'    => true,
	#    'by_reference' => false,
	#    'allow_delete' => true,
        #    'label'          => '.',
	#    'prototype_name' => '__hora__',
            #'prototype_data' => new HorarioObra(),
            #'attr'           => array(
            #    'class' => 'row addresses'
            #    )
	#    ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\ReservasBundle\Entity\FechaObra',
        ));
    }

    public function getName()
    {
        return 'fechasobra';
    }
}