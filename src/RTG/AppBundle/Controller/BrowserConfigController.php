<?php
namespace RTG\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BrowserConfigController extends Controller
{

    /**
     * @Route("/browserconfig.{_format}", Requirements={"_format" = "xml"})
     * @Template("RTGAppBundle:BrowserConfig:browserconfig.xml.twig")
     */
    public function browserConfigAction() 
    {
        return array();
    }
}