<?php

namespace RTG\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('label' => 'user.field.username'))
            ->add('email', 'text', array('label' => 'user.field.email'))
            ->add('enabled', 'checkbox', array('label' => 'user.field.enabled'))
            ->add('locked', 'checkbox', array('label' => 'user.field.locked'))
            ->add('roles', null, array('label' => 'user.field.roles'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RTG\UserBundle\Entity\User',
            'translation_domain' => 'entity'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_userbundle_user';
    }
}
