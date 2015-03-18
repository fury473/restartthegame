<?php

namespace RTG\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use RTG\BlogBundle\Form\ImageArticleType;
use RTG\BlogBundle\Entity\Category;

class NewsArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $options['data'];
        if($entity->getId() == null) {
            $builder->add('newsletter', 'checkbox', array('label' => 'general.newsletter', 'required' => false, 'mapped' => false));
        }
        $builder
            ->add('featured', 'checkbox', array('label' => 'general.featured', 'required' => false))
            ->add('title', 'text', array('label' => 'general.title', 'attr' => array('class' => 'form-control')))
            ->add('catchPhrase', 'text', array('label' => 'general.catchPhrase', 'attr' => array('class' => 'form-control')))
            ->add('category', 'entity', array(
                'class' => 'RTGBlogBundle:Category',
                'empty_value' => 'Aucune',
                'required' => false,
                'label' => 'general.category',
                'attr' => array('class' => 'form-control')
            ))
            ->add('message', 'ckeditor', array(
                'label' => 'general.message',
                'attr' => array('class' => 'form-control')
            ));
        if($entity->getImage() == null) {
            $builder->add('image', new ImageArticleType(), array('label' => 'general.image'));
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RTG\BlogBundle\Entity\NewsArticle',
            'translation_domain' => 'form'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_blogbundle_admin_news_article';
    }
}
