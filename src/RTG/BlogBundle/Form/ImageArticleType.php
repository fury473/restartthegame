<?php

namespace RTG\BlogBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use RTG\SiteBundle\Form\ImageType;

class ImageArticleType extends ImageType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RTG\BlogBundle\Entity\ImageArticle',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_blogbundle_admin_image_article';
    }
}
