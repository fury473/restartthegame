<?php

namespace RTG\UserBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use RTG\UserBundle\Entity\User;
use RTG\UserBundle\Form\Admin;

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

        return array(
            'user' => $user,
            'form' => $editForm->createView()
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/profile/{id}")
     * @Method("PUT")
     * @Template("RTGUserBundle:Admin\User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('RTGUserBundle:User')->find($id);
        $original_user = clone $user;
        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
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
            
            return $this->redirectToEdit($id);
        }

        return array(
            'user' => $original_user,
            'form' => $editForm->createView()
        );
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
        $form = $this->createForm(new Admin\UserType(), $entity, array(
            'action' => $this->generateUrl('rtg_user_admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('user.button.update', array(), 'entity'), 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    
    protected function redirectToEdit($id)
    {
        return $this->redirect($this->generateUrl('rtg_user_admin_user_edit', array('id' => $id)));
    }

}
