<?php

namespace RTG\BlogBundle\Controller;

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
 * @Route("/")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/{slug}/index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('RTGBlogBundle:Category')->findOneBySlug($slug);
        $articles = $em->getRepository('RTGBlogBundle:NewsArticle')->getLatestArticles($category);
        return array(
            'category' => $category,
            'articles' => $articles
        );
    }
    
}