<?php

namespace RTG\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use RTG\AppBundle\Form\ContactType;

/**
 * Page controller.
 *
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('RTGBlogBundle:NewsArticle')->getFeaturedArticles(5);
        $competitions = $em->getRepository('RTGBlogBundle:CompetitionArticle')->getLatestArticles(null, 5);
        return array(
            'news' => $news,
            'competitions' => $competitions
        );
    }
    
    /**
     * @Template()
     */
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('RTGBlogBundle:Category')->findAll();
        return array(
            'categories' => $categories
        );
    }
    
    /**
     * @Template()
     */
    public function menuUserAction()
    {
        return array();
    }
    
    /**
     * @Route("/admin")
     * @Method({"GET"})
     * @Template()
     */
    public function adminAction()
    {
        return array();
    }
    
    /**
     * @Route("/about-us")
     * @Method({"GET"})
     * @Template()
     */
    public function aboutUsAction()
    {
        return array();
    }
    
    /**
     * @Route("/partners")
     * @Method({"GET"})
     * @Template()
     */
    public function partnersAction()
    {
        return array();
    }
    
    /**
     * @Route("/teamspeak")
     * @Method({"GET"})
     * @Template()
     */
    public function teamspeakAction()
    {
        return array();
    }
    
    /**
     * @Route("/contact")
     * @Method({"GET"})
     * @Template()
     */
    public function contactAction()
    {
        $route = $this->generateUrl('rtg_app_page_contactsubmit');
        $form = $this->createForm(new ContactType(), null, array('action' => $route));
        return array('form' => $form->createView());
    }
    
    /**
     * @Route("/contact-submit")
     * @Method({"POST"})
     * @Template()
     */
    public function contactSubmitAction(Request $request)
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);
        
        if(($errorList = $form->isValid()) == true) {
            $other_object = $form->get('other_object')->getData();
            if($other_object == null || $form->get('object')->getData() != 'Autre') {
                $subject = $form->get('object')->getData();
            } else {
                $subject = $other_object;
            }
            $from = $this->container->getParameter('site_address');
            $to = $this->container->getParameter('contact_delivery_address');
            $content = $form->get('message')->getData();

            $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($content);
            
            $logger = $this->get('monolog.logger.ip_mails');
            $logger->warning($this->get('request')->getClientIp() . " have sent a mail from contact form.");
            
            $this->get('mailer')->send($message);

            return array('to' => $to);
        } else {
            return $this->render('RTGAppBundle:Page:contact.html.twig', array('form' => $form->createView(), 'errorList' => $errorList));
        }
    }
    
    /**
     * @Route("/legal-notices")
     * @Method({"GET"})
     * @Template()
     */
    public function legalNoticesAction()
    {
        return array();
    }
    
    /**
     * @Route("/forum")
     * @Method({"GET"})
     */
    public function forumAction()
    {
        return $this->redirect("http://forum.restartthegame.com");
    }
    
    
    
//    /**
//     * @Route("/coming-soon")
//     * @Method({"GET"})
//     * @Template()
//     */
//    public function comingSoonAction()
//    {
//        return array();
//    }
}
