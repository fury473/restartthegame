<?php

namespace RTG\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \RTG\BlogBundle\Entity\NewsComment;
use \RTG\BlogBundle\Form\NewsCommentType;

/**
 * NewsComment controller.
 *
 * @Route("/comment")
 */
class NewsCommentController extends Controller
{
    /**
     * @Route("/{article_id}/new")
     * @Method({"GET"})
     * @Template("RTGBlogBundle:NewsComment:form.html.twig")
     */
     public function newAction($article_id)
    {
        $article = $this->getArticle($article_id);
        $user = $this->get('security.context')->getToken()->getUser();

        $comment = new NewsComment();
        $comment->setArticle($article);
        $form   = $this->createForm(new NewsCommentType(), $comment);

        return array(
            'comment' => $comment,
            'form'   => $form->createView(),
            'user' => $user
        );
    }

    /**
     * @Route("/{article_id}/create")
     * @Method({"POST"})
     * @Template("RTGBlogBundle:NewsComment:create.html.twig")
     */
    public function createAction($article_id)
    {
        if($this->get('security.context')->isGranted('ROLE_USER')) {
            $article = $this->getArticle($article_id);

            $comment  = new NewsComment();
            $comment->setUser($this->get('security.context')->getToken()->getUser());
            $comment->setArticle($article);
            $request = $this->getRequest();
            $form    = $this->createForm(new NewsCommentType(), $comment);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->redirect($this->generateUrl('rtg_blog_newsarticle_show', array(
                    'id' => $comment->getArticle()->getId(),
                    'slug' => $comment->getArticle()->getSlug())) .
                    '#comment-' . $comment->getId()
                );
            }

            return array(
                'comment' => $comment,
                'form'    => $form->createView()
            );
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
    }

    protected function getArticle($article_id)
    {
        $em = $this->getDoctrine()
                    ->getManager();

        $article = $em->getRepository('RTGBlogBundle:NewsArticle')->find($article_id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find NewsArticle post.');
        }

        return $article;
    }
}
