<?php

namespace RTG\UserBundle\Form\SuperAdmin;

use RTG\UserBundle\Form\Admin\UserType as BaseUserType;
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
                ->add('roles', 'collection', array(
                    'label' => 'user.field.roles',
                    'type' => 'text',
                    'allow_add' => true,
                    'allow_delete' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_userbundle_superadmin_useredit';
    }

}
