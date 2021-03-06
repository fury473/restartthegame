<?php

namespace RTG\UserBundle\Form\Admin;

use RTG\UserBundle\Form\ProfileFormType as BaseUserType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends BaseUserType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('function', 'text', array('label' => 'user.field.function', 'required' => false, 'attr' => array('class' => 'form-control')))
                ->add('enabled', 'checkbox', array('label' => 'user.field.enabled', 'required' => false))
                ->add('locked', 'checkbox', array('label' => 'user.field.locked', 'required' => false))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_userbundle_admin_useredit';
    }

}
