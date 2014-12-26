<?php

namespace RTG\UserBundle\Form\Admin;

use RTG\UserBundle\Entity\Avatar;
use RTG\UserBundle\Form\AvatarType;
use RTG\UserBundle\Form\ProfileFormType as BaseUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
