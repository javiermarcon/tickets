<?php

namespace Acme\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            //si es solo cambio de contraseña
	    $builder->add('oldPassword', 'password', array(
            'label' => 'Contraseña Anterior: ', 'required' => True
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
        $resolver->setDefaults(array(
            'data_class' => 'Acme\UserBundle\Form\Model\ChangePassword',
        ));
    }

    public function getName()
    {
        return 'change_passwd';
    }
}