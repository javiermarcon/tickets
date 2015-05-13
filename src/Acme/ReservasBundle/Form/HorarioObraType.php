<?php

namespace Acme\ReservasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HorarioObraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hora')
            ->add('fechaobra')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\ReservasBundle\Entity\HorarioObra'
        ));
    }

    public function getName()
    {
        return 'acme_reservasbundle_horarioobra';
    }
}
