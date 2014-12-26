<?php

namespace RTG\UserBundle\Form;

use RTG\UserBundle\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();
        if ($user->getAvatar()) {
            $this->originalAvatar = clone $user->getAvatar();
        }
        $builder
                ->add('username', 'text', array('label' => 'user.field.username', 'attr' => array('class' => 'form-control')))
                ->add('email', 'email', array('label' => 'user.field.email', 'attr' => array('class' => 'form-control')))
                ->add('newsletter', 'checkbox', array('required' => false))
                ->add('birthday', 'date', array(
                    'years' => $this->getYears(),
                    'label' => 'user.field.birthday',
                    'required' => false
                ))
                ->add('city', 'text', array('label' => 'user.field.city', 'required' => false, 'attr' => array('class' => 'form-control')))
                ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
                ->addEventListener(FormEvents::SUBMIT, array($this, 'onSubmit'))
        ;
        if($this->originalAvatar == null) {
            $builder
                    ->add('avatar', new AvatarType(), array('label' => 'user.field.avatar', 'required' => false))
            ;
        }
        if ($user->getAvatar() != null) {
            $builder->add('delete_avatar', 'checkbox', array('required' => false, 'mapped' => false));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RTG\UserBundle\Entity\User',
            'translation_domain' => 'entity',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rtg_userbundle_user_profile';
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $this->deleteAvatar = false;
        if (isset($data['delete_avatar']) && $data['delete_avatar']) {
            $this->deleteAvatar = true;
        }
    }

    public function onSubmit(FormEvent $event)
    {
        if ($this->originalAvatar) {
            if (!$this->deleteAvatar) {
                $avatar = $event->getForm()->getData()->getAvatar();
                $avatar->setFile($this->originalAvatar->getFile());
            } elseif ($this->deleteAvatar) {
                $event->getForm()->getData()->setAvatar(null);
            }
        }
    }

    private function getYears()
    {
        $today = new \DateTime();
        $year = $today->format('Y');
        $years = array();
        for ($i = 0; $i <= 112; $i++) {
            $years[] = $year - $i;
        }
        return $years;
    }

    private $originalAvatar;
    private $deleteAvatar;

}

?>