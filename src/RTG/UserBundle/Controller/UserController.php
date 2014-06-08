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
class UserController extends Controller {

    /**
     * @Route("/index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        // Pour récupérer le service UserManager du bundle
        $userManager = $this->get('fos_user.user_manager');

        // Pour charger un utilisateur
        $user = $userManager->findUserBy(array('username' => 'winzou'));
        
        $userManager->updateUser($user); // Pas besoin de faire un flush avec l'EntityManager, cette méthode le fait toute seule !
        //
        // Pour supprimer un utilisateur
        $userManager->deleteUser($user);

        // Pour récupérer la liste de tous les utilisateurs
        $users = $userManager->findUsers();
        return array();
    }
    
    /**
     * @Route("/profile")
     * @Method("GET")
     * @Template()
     */
    public function myProfileAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        return array('user' => $user);
    }
    
    /**
     * @Route("/profile/{id}/show")
     * @Method("GET")
     * @Template()
     */
    public function profileAction($id) {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        return array('user' => $user);
    }
    
    /**
     * @Route("/profile/edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction() {
        $user = $this->get('security.context')->getToken()->getUser();

        $editForm = $this->createEditForm($user);
        $deleteForm = $this->createDeleteForm();
        
        $parameters = array(
            'user'      => $user,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
        
        if($user->getAvatar() != null) {
            $deleteImgForm = $this->createDeleteImgForm($user->getAvatar()->getId());
            $parameters['delete_img_form'] = $deleteImgForm->createView();
        }

        return $parameters;
    }
    
    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new ProfileFormType(), $entity, array(
            'action' => $this->generateUrl('rtg_user_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.update', array(), 'form'), 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    
    /**
     * @Route("/profile")
     * @Method("PUT")
     * @Template("RTGUserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request) {
        $userManager = $this->get('fos_user.user_manager');

        $entity = $this->get('security.context')->getToken()->getUser();

        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $avatar = $entity->getAvatar();
        if($avatar->getFile() == null) {
            $entity->setAvatar(null);
        }

        if ($editForm->isValid()) {
            $userManager->updateUser($entity);

            return $this->redirect($this->generateUrl('rtg_user_user_edit'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * @Route("/profile/delete")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function deleteAction(Request $request) {
        if($this->get('security.context')->isGranted('ROLE_USER')) {
            $state = 'get';
            if($request->getMethod() == "POST") {
                $state = 'invalid_password';
                $user = $this->container->get('security.context')->getToken()->getUser();
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $typedPassword = $encoder->encodePassword($request->request->get('password'), $user->getSalt());
                $userPassword = $user->getPassword();

                if($typedPassword == $userPassword) {
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
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_user_user_delete'))
            ->setMethod('DELETE')
           ->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.delete_account', array(), 'entity'), 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
    
    /**
     * Deletes a ImageArticle entity.
     *
     * @Route("/profile/image/{id}")
     * @Method("DELETE")
     */
    public function deleteImgAction(Request $request, $id)
    {
        $form = $this->createDeleteImgForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image = $em->getRepository('RTGUserBundle:Avatar')->find($id);

            if (!$image) {
                throw $this->createNotFoundException('Unable to find Avatar entity.');
            }

            $em->remove($image);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rtg_user_user_edit'));
    }
    
    /**
     * Creates a form to delete image by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteImgForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_user_user_deleteimg', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $this->get('translator')->trans('image.delete', array(), 'form'), 'attr' => array('class' => 'btn btn-warning')))
            ->getForm()
        ;
    }

}
