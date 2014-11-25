<?php

namespace RTG\BlogBundle\Controller;

use RTG\BlogBundle\Entity\CompetitionArticle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * CompetitionArticle controller.
 *
 * @Route("/competition")
 */
class CompetitionArticleController extends Controller
{
    
    /**
     * @Route("/ajax-toggle-registration/{id}")
     * @ParamConverter("competition", class="RTGBlogBundle:CompetitionArticle")
     * @Method("POST")
     */
    public function ajaxToggleRegistrationAction(Request $request, CompetitionArticle $competition)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new \Exception("Must be XmlHttpRequest", 403);
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $registered = false;
        if($competition->hasUser($user)) {
            $competition->removeUser($user);
        } else {
            $competition->addUser($user);
            $registered = true;
        }
        
        $em->persist($competition);
        $em->flush();
        
        return new JsonResponse(array('registered' => $registered));
    }
    
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
