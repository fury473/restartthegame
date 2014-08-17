<?php

namespace RTG\BlogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use RTG\BlogBundle\Entity\CompetitionArticle;
use RTG\BlogBundle\Form\CompetitionArticleType;

/**
 * CompetitionArticle controller.
 *
 * @Route("/admin/competition")
 */
class CompetitionArticleController extends Controller
{

    /**
     * Lists all CompetitionArticle entities.
     *
     * @Route("/index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('RTGBlogBundle:CompetitionArticle')->findAll();
        return array(
            'entities' => $articles
        );
    }
    /**
     * Creates a new CompetitionArticle entity.
     *
     * @Route("/")
     * @Method("POST")
     * @Template("RTGBlogBundle:Admin\CompetitionArticle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CompetitionArticle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $image = $entity->getImage();
        if($image->getFile() == null) {
            $entity->setImage(null);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
//            if($form->get('newsletter')->getData()) {
//                $subject = $form->get('title')->getData();
//                $content = $form->get('message')->getData();
//                $this->get('newsletter')->send($subject, $content);
//            }

            return $this->redirect($this->generateUrl('rtg_blog_competitionarticle_show', array('slug' => $entity->getSlug())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a CompetitionArticle entity.
    *
    * @param CompetitionArticle $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CompetitionArticle $entity)
    {
        $form = $this->createForm(new CompetitionArticleType(), $entity, array(
            'action' => $this->generateUrl('rtg_blog_admin_competitionarticle_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('general.create', array(), 'form'), 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new CompetitionArticle entity.
     *
     * @Route("/new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CompetitionArticle();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing CompetitionArticle entity.
     *
     * @Route("/{id}/edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RTGBlogBundle:CompetitionArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetitionArticle entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        
        if($entity->getImage() != null) {
            $deleteImgForm = $this->createDeleteImgForm($entity->getId(), $entity->getImage()->getId());
            return array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'delete_img_form' => $deleteImgForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a CompetitionArticle entity.
    *
    * @param CompetitionArticle $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CompetitionArticle $entity)
    {
        $form = $this->createForm(new CompetitionArticleType(), $entity, array(
            'action' => $this->generateUrl('rtg_blog_admin_competitionarticle_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('general.update', array(), 'form'), 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing CompetitionArticle entity.
     *
     * @Route("/{id}")
     * @Method("PUT")
     * @Template("RTGBlogBundle:Admin\CompetitionArticle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RTGBlogBundle:CompetitionArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompetitionArticle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $image = $entity->getImage();
        if($image->getFile() == null) {
            $entity->setImage(null);
        }

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('rtg_blog_admin_competitionarticle_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a CompetitionArticle entity.
     *
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RTGBlogBundle:CompetitionArticle')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CompetitionArticle entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rtg_blog_admin_competitionarticle_index'));
    }
    
    /**
     * Creates a form to delete a CompetitionArticle entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_blog_admin_competitionarticle_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $this->get('translator')->trans('general.delete', array(), 'form'), 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
    
    /**
     * Deletes a ImageArticle entity.
     *
     * @Route("/{article_id}/image/{id}")
     * @Method("DELETE")
     */
    public function deleteImgAction(Request $request, $article_id, $id)
    {
        $form = $this->createDeleteImgForm($article_id, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image = $em->getRepository('RTGBlogBundle:ImageArticle')->find($id);

            if (!$image) {
                throw $this->createNotFoundException('Unable to find ImageArticle entity.');
            }

            $em->remove($image);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rtg_blog_admin_competitionarticle_edit', array('id' => $article_id)));
    }
    
    /**
     * Creates a form to delete image by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteImgForm($article_id, $id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_blog_admin_competitionarticle_deleteimg', array('article_id' => $article_id, 'id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $this->get('translator')->trans('image.delete', array(), 'form'), 'attr' => array('class' => 'btn btn-warning')))
            ->getForm()
        ;
    }
}