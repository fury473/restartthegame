<?php

namespace RTG\UserBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RTG\UserBundle\Entity\User;
use RTG\UserBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $entities = $userManager->findUsers();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new User entity.
     *
     * @Route("/")
     * @Method("POST")
     * @Template("RTGUserBundle:Admin\User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $avatar = $entity->getAvatar();
        if($avatar->getFile() == null) {
            $entity->setAvatar(null);
        }

        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($entity);

            return $this->redirect($this->generateUrl('rtg_user_admin_user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('rtg_user_admin_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $userManager = $this->get('fos_user.user_manager');

        $entity = $userManager->findUserBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $userManager = $this->get('fos_user.user_manager');

        $entity = $userManager->findUserBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('rtg_user_admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.update', array(), 'entity')));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}")
     * @Method("PUT")
     * @Template("RTGUserBundle:Admin\User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');

        $entity = $userManager->findUserBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $avatar = $entity->getAvatar();
        if($avatar->getFile() == null) {
            $entity->setAvatar(null);
        }

        if ($editForm->isValid()) {
            $userManager->updateUser($entity);

            return $this->redirect($this->generateUrl('rtg_user_admin_user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $entity = $userManager->findUserBy(array('id' => $id));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $userManager->deleteUser($entity);
        }

        return $this->redirect($this->generateUrl('rtg_user_admin_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_user_admin_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.delete_this_account', array(), 'entity')))
            ->getForm()
        ;
    }
    
    /**
     * Deletes a ImageArticle entity.
     *
     * @Route("/{user_id}/image/{id}")
     * @Method("DELETE")
     */
    public function deleteImgAction(Request $request, $user_id, $id)
    {
        $form = $this->createDeleteImgForm($user_id, $id);
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

        return $this->redirect($this->generateUrl('rtg_user_admin_user_edit', array('id' => $user_id)));
    }
    
    /**
     * Creates a form to delete image by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteImgForm($user_id, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_user_admin_user_deleteimg', array('user_id' => $user_id, 'id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $this->get('translator')->trans('image.delete', array(), 'form'), 'attr' => array('class' => 'btn btn-default')))
            ->getForm()
        ;
    }
}
