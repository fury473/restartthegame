<?php

namespace RTG\BlogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use RTG\BlogBundle\Entity\Category;
use RTG\BlogBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('RTGBlogBundle:Category')->findAll();
        return array(
            'entities' => $categories
        );
    }
    
    /**
     * List of Articles that can be added.
     *
     * @Route("/{slug}/add-articles")
     * @Method("GET")
     * @Template()
     */
    public function addArticlesAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);
        $articles = $em->getRepository('RTGBlogBundle:NewsArticle')->findByCategoryOrNull($category);
        return array(
            'category' => $category,
            'articles' => $articles
        );
    }
    
    /**
     * Fetch selected articles into Category.
     *
     * @Route("/{slug}/add-articles")
     * @Method("POST")
     * @Template()
     */
    public function addArticlesSubmitAction(Request $request, $slug)
    {
        $article_ids = $request->request->get('article_ids');
        $original_articles = array();
        $original_article_ids = array();
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);
        foreach($category->getArticles() as $article) {
            $original_article_ids[] = $article->getId();
            $original_articles[] = $article;
        }
        foreach($original_articles as $article) {
            if($article_ids == null || !in_array($article->getId(), $article_ids)) {
                $category->removeArticle($article);
            }
        }
        if($article_ids != null) {
            foreach($article_ids as $article) {
                if(!in_array($article, $original_article_ids)) {
                    $article = $em->getRepository('RTGBlogBundle:NewsArticle')->find($article);
                    $category->addArticle($article);
                }
            }
        }
        $em->flush();
        $em->persist($category);
        return $this->redirect($this->generateUrl('rtg_blog_admin_category_addarticles', array('slug' => $category->getSlug())));
    }
    
    /**
     * Creates a new Category entity.
     *
     * @Route("/")
     * @Method("POST")
     * @Template("RTGBlogBundle:Admin\Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('rtg_blog_admin_category_show', array('slug' => $entity->getSlug())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('rtg_blog_admin_category_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('general.create', array(), 'form'), 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Category();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{slug}/edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($slug);
        
        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('rtg_blog_admin_category_update', array('slug' => $entity->getSlug())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('general.update', array(), 'form'), 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    
    /**
     * Lists all Articles of a Category.
     *
     * @Route("/{slug}/show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);
        $articles = $em->getRepository('RTGBlogBundle:NewsArticle')->findByCategory($category);
        return array(
            'category' => $category,
            'articles' => $articles
        );
    }
    
    /**
     * Edits an existing Category entity.
     *
     * @Route("/{slug}")
     * @Method("PUT")
     * @Template("RTGBlogBundle:Admin\Category:edit.html.twig")
     */
    public function updateAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($slug);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('rtg_blog_admin_category_edit', array('slug' => $slug)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Category entity.
     *
     * @Route("/{slug}")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $slug)
    {
        $form = $this->createDeleteForm($slug);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Category entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rtg_blog_admin_category_index'));
    }
    
    /**
     * Creates a form to delete a Category entity by slug.
     *
     * @param mixed $slug The entity slug
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rtg_blog_admin_category_delete', array('slug' => $slug)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => $this->get('translator')->trans('general.delete', array(), 'form'), 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }
}