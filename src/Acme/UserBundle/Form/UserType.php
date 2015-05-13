<?php

namespace Acme\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('telefono')
            ->add('celular')
            ->add('usertype', 'choice', array(
    'choices'  => array('Usuario' => 'Usuario', 'Administrador' => 'Administrador'),
    'required' => true,
))
            #->add('isActive')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acme\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'acme_userbundle_user';
    }
}
