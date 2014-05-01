<?php

namespace RTG\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * NewsArticle controller.
 *
 * @Route("/news")
 */
class NewsArticleController extends Controller
{
    
    /**
     * Lists all articles entities
     *
     * @Route("/index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('RTGBlogBundle:NewsArticle')->getLatestArticles();
        return array(
            'articles' => $articles
        );
    }
        
    

    /**
     * Show an article
     * @Route("/{slug}")
     * @ParamConverter("article", class="RTGBlogBundle:NewsArticle", options={"id" = "slug", "repository_method" = "findOneBySlug"})
     * @ParamConverter("comments", class="RTGBlogBundle:NewsComment", options={"id" = "article", "repository_method" = "getCommentsForArticle"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($article, $comments)
    {
        return array(
            'article'      => $article,
            'comments'      => $comments,
        );
    }
}
