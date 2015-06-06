<?php
namespace RTG\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SitemapController extends Controller
{

    /**
     * @Route("/sitemap.{_format}", Requirements={"_format" = "xml"})
     * @Template("RTGAppBundle:Sitemap:sitemap.xml.twig")
     */
    public function sitemapAction() 
    {
        $em = $this->getDoctrine()->getManager();
        
        $urls = array();
        $hostname = $this->getRequest()->getHost();
        $router = $this->get('router');

        // Add urls for all static pages
        foreach($this->getStaticRoutes() as $route) {
            $urls[] = array('loc' => $router->generate($route));
        }
        
        // Add urls for all category indexes
        foreach ($em->getRepository('RTGBlogBundle:Category')->findAll() as $category) {
            $urls[] = array('loc' => $router->generate('rtg_blog_newsarticle_indexbycategory', 
                    array('slug' => $category->getSlug())));
        }
        
        // Add urls for all news
        foreach ($em->getRepository('RTGBlogBundle:NewsArticle')->findAll() as $article) {
            $urls[] = array('loc' => $router->generate('rtg_blog_newsarticle_show', 
                    array('slug' => $article->getSlug())));
        }
        
        // Add urls for all competitions
        foreach ($em->getRepository('RTGBlogBundle:CompetitionArticle')->findAll() as $competition) {
            $urls[] = array('loc' => $router->generate('rtg_blog_competitionarticle_show', 
                    array('slug' => $competition->getSlug())));
        }

        return array('urls' => $urls, 'hostname' => $hostname);
    }
    
    private function getStaticRoutes()
    {
        $routes = array();
        $routes[] = 'rtg_app_page_index';
        $routes[] = 'rtg_blog_newsarticle_index';
        $routes[] = 'rtg_blog_competitionarticle_index';
        $routes[] = 'rtg_app_page_tv';
        $routes[] = 'rtg_app_page_aboutus';
        $routes[] = 'rtg_app_page_teamspeak';
        $routes[] = 'rtg_app_page_partners';
        $routes[] = 'rtg_app_page_contact';
        $routes[] = 'rtg_app_page_legalnotices';
        return $routes;
    }
}