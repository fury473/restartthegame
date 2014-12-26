<?php

namespace RTG\UserBundle\Controller\SuperAdmin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use RTG\UserBundle\Controller\Admin\UserController as BaseUserController;
use RTG\UserBundle\Entity\User;
use RTG\UserBundle\Form\SuperAdmin;

/**
 * User controller.
 *
 * @Route("/super-admin/user")
 */
class UserController extends BaseUserController
{

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/profile/{id}")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('RTGUserBundle:User')->findOneBy(array('id' => $id));
        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($user);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'user' => $user,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/profile/{id}")
     * @Method("POST")
     * @Template("RTGUserBundle:SuperAdmin\User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $response = parent::updateAction($request, $id);
        if($response instanceof RedirectResponse) {
            return $response;
        }
        $deleteForm = $this->createDeleteForm($id);
        $response['delete_form'] = $deleteForm->createView();
        return $response;
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/profile/{id}")
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

        return $this->redirect($this->generateUrl('rtg_user_superadmin_user_index'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('rtg_user_superadmin_user_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.delete_this_account', array(), 'entity'), 'attr' => array('class' => 'btn btn-danger')))
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
        $form = $this->createForm(new SuperAdmin\UserType(), $entity, array(
            'action' => $this->generateUrl('rtg_user_superadmin_user_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.update', array(), 'entity'), 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    
    protected function redirectToEdit($id)
    {
        return $this->redirect($this->generateUrl('rtg_user_superadmin_user_edit', array('id' => $id)));
    }

}
