<?php

namespace RTG\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RTG\UserBundle\Entity\User;
use RTG\UserBundle\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * @Route("/profile")
     * @Method("GET")
     * @Template()
     */
    public function myProfileAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $editForm = $this->createEditForm($user);

        return array(
            'user' => $user,
            'form' => $editForm->createView()
        );
    }

    /**
     * @Route("/profile/{id}")
     * @Method("GET")
     * @Template()
     */
    public function profileAction($id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        return array('user' => $user);
    }

    /**
     * @Route("/profile")
     * @Method("POST")
     * @Template("RTGUserBundle:User:myProfile.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();
        $original_user = clone $user;
        $avatar = null;
        if ($user->getAvatar()) {
            $avatar = $em->getRepository('RTGUserBundle:Avatar')->find($user->getAvatar()->getId());
        }

        $editForm = $this->createEditForm($user);
        $profile_handled = $request->get($editForm->getName());
        $delete_avatar = false;
        if (isset($profile_handled['delete_avatar'])) {
            $delete_avatar = true;
        }
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($delete_avatar) {
                $em->remove($avatar);
            }
            $em->persist($user);
            $em->flush();
            
            return $this->redirectToProfile();
        }

        return array(
            'user' => $original_user,
            'form' => $editForm->createView()
        );
    }

    /**
     * @Route("/profile-delete")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function deleteAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            $state = 'get';
            if ($request->getMethod() == "POST") {
                $state = 'invalid_password';
                $user = $this->container->get('security.context')->getToken()->getUser();
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $typedPassword = $encoder->encodePassword($request->request->get('password'), $user->getSalt());
                $userPassword = $user->getPassword();

                if ($typedPassword == $userPassword) {
                    $this->container->get('fos_user.user_manager')->deleteUser($user);
                    $state = 'deleted';
                }
            }

            return array('state' => $state);
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm()
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('rtg_user_user_delete'))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.delete_account', array(), 'entity'), 'attr' => array('class' => 'btn btn-danger')))
                        ->getForm()
        ;
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm(User $entity)
    {
        $form = $this->createForm(new ProfileFormType(), $entity, array(
            'action' => $this->generateUrl('rtg_user_user_update'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.update', array(), 'form'), 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    
    protected function redirectToProfile()
    {
        return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
    }

}
