<?php

namespace Acme\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array(
                'required' => true))
            ->add('apellido')
            ->add('username','text', array(
                'required' => true))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las 2 contraseñas deben coincidir.',
                'required' => true,
                'first_options'  => array('label' => 'Contraseña: '),
                'second_options' => array('label' => 'Repetir Contraseña: '),
                ))
            ->add('email', 'email', array(
                'required' => true))
            ->add('telefono')
            ->add('celular')
	    ->add('usertype', 'hidden', array(
		'data' => 'Usuario',))
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
