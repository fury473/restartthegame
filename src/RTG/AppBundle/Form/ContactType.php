<?php

namespace RTG\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('object', 'choice', array(
                    'choices' => array(
                        'Adhésion à la RTG' => 'Adhésion à la RTG',
                        'Concours' => 'Concours',
                        'Problème(s) à propos de Teamspeak' => 'Problème(s) à propos de Teamspeak',
                        'Problème(s) à propos du Site' => 'Problème(s) à propos du Site',
                        'Autre' => 'Autre'
                    ),
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Objet',
                    'required' => true
                ))
                ->add('other_object', 'text', array(
                    'label' => 'Précisez l\'objet de votre demande',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ))
                ->add('message', 'textarea', array(
                    'attr' => array(
                        'class' => 'form-control',
                        'cols' => 65,
                        'rows' => 9
                    ),
                    'label' => false
                ))
                ->add('send', 'submit', array('label' => 'Envoyer'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'rtg_AppBundle_contact';
    }

}
