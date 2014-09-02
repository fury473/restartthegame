<?php

namespace RTG\UserBundle\Form\SuperAdmin;

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
        $entity = $builder->getData();
        $builder
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
            ->add('roles', 'collection', array(
                'label' => 'user.field.roles',
                'type' => 'text',
                'allow_add' => true,
                'allow_delete' => true
            ))
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
