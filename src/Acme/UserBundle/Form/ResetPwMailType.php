<?php

namespace Acme\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResetPwMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('email', 'email', array(
            'label' => 'Email del usuario: ', 'required' => True
        ));
        $builder->add('save', 'submit', array('label' => 'Grabar'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array());
    }

    public function getName()
    {
        return 'reset_pw_init';
    }
}