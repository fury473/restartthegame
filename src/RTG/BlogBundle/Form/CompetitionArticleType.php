<?php

namespace RTG\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use RTG\BlogBundle\Entity\CompetitionArticle;
use RTG\BlogBundle\Form\ImageArticleType;

class CompetitionArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $options['data'];
        if($entity->getId() == null) {
            $builder->add('newsletter', 'checkbox', array('required' => false, 'mapped' => false));
        }
        $builder
            ->add('title', 'text', array('label' => 'general.title', 'attr' => array('class' => 'form-control')))
            ->add('status', 'choice', array(
                'choices' => array(
                    CompetitionArticle::EventScheduled => 'Planifiée',
                    CompetitionArticle::EventRescheduled => 'Replanifiée',
                    CompetitionArticle::EventPostponed => 'Repoussée',
                    CompetitionArticle::EventCancelled => 'Annulée',
                ),
                'attr' => array('class' => 'form-control')
            ))
            ->add('date', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'label' => 'general.date',
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
            'data_class' => 'RTG\BlogBundle\Entity\CompetitionArticle',
            'translation_domain' => 'form'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_blogbundle_admin_competition_article';
    }
}
