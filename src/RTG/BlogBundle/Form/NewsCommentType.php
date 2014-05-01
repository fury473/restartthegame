<?php

namespace RTG\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsCommentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', 'textarea', array(
                'label' => false,
                'attr' => array('class' => 'form-control', 'placeholder' => 'comment.message')
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RTG\BlogBundle\Entity\NewsComment',
            'translation_domain' => 'form'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_blogbundle_newscomment';
    }
}
