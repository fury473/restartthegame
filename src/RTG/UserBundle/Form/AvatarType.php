<?php

namespace RTG\UserBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use RTG\AppBundle\Form\ImageType;

class AvatarType extends ImageType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RTG\UserBundle\Entity\Avatar',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_userbundle_admin_avatar';
    }
}
