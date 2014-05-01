<?php

namespace RTG\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * CompetitionArticle controller.
 *
 * @Route("/competition")
 */
class CompetitionArticleController extends Controller
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
        $articles = $em->getRepository('RTGBlogBundle:CompetitionArticle')->getLatestArticles();
        return array(
            'articles' => $articles
        );
    }
        
    

    /**
     * Show an article
     * @Route("/{slug}")
     * @ParamConverter("article", class="RTGBlogBundle:CompetitionArticle", options={"id" = "slug", "repository_method" = "findOneBySlug"})
     * @Method("GET")
     * @Template()
     */
    public function showAction($article)
    {
        return array(
            'article'      => $article
        );
    }
}
