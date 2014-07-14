<?php

namespace RTG\UserBundle\Form\Admin;

use RTG\UserBundle\Form\AvatarType;
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
        $entity = $options['data'];
        $builder
            ->add('username', 'text', array('label' => 'user.field.username', 'attr' => array('class' => 'form-control')))
            ->add('email', 'email', array('label' => 'user.field.email', 'attr' => array('class' => 'form-control')))
            ->add('newsletter', 'checkbox', array('label' => 'user.field.newsletter', 'required' => false))
            ->add('birthday', 'date', array(
                'label' => 'user.field.birthday',
                'widget' => 'single_text',
                'attr' => array('class' => 'datepicker'),
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('city', 'text', array('label' => 'user.field.city', 'required' => false, 'attr' => array('class' => 'form-control')))
            ->add('enabled', 'checkbox', array('label' => 'user.field.enabled', 'required' => false))
            ->add('locked', 'checkbox', array('label' => 'user.field.locked', 'required' => false))
            /*->add('roles', 'choice', array(
                'label' => 'user.field.roles',
                'expanded' => true,
                'multiple' => true,
                'choices' => array(
                    'ROLE_USER',
                    'ROLE_STAFF',
                    'ROLE_ADMIN',
                    'ROLE_SUPER_ADMIN'
                )
            ))*/
        ;
        if($entity->getAvatar() == null) {
            $builder->add('avatar', new AvatarType(), array('label' => 'user.field.avatar'));
        }
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
