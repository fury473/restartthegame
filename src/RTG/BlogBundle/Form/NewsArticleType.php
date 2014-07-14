<?php

namespace RTG\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use RTG\BlogBundle\Form\ImageArticleType;

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
            $builder->add('newsletter', 'checkbox', array('label' => 'article.newsletter', 'required' => false, 'mapped' => false));
        }
        $builder
            ->add('featured', 'checkbox', array('label' => 'article.featured', 'required' => false))
            ->add('title', 'text', array('label' => 'article.title', 'attr' => array('class' => 'form-control')))
            ->add('catchPhrase', 'text', array('label' => 'article.catchPhrase', 'attr' => array('class' => 'form-control')))
            ->add('message', 'ckeditor', array('label' => 'article.message', 'attr' => array('class' => 'form-control')));
        if($entity->getImage() == null) {
            $builder->add('image', new ImageArticleType(), array('label' => 'article.image'));
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
