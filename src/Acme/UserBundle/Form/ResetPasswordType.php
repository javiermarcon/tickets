<?php

namespace Acme\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	$builder->add('codigo', 'text', array(
            'label' => 'Codigo recibido: ', 'required' => True
        ));
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options'  => array('label' => 'Contraseña nueva: '),
            'second_options' => array('label' => 'Repetir Contraseña: '),
            
        ));
        $builder->add('save', 'submit', array('label' => 'Grabar'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    public function getName()
    {
        return 'reset_passwd';
    }
}