<?php

namespace RTG\AppBundle\Controller;

use RTG\AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;

/**
 * Page controller.
 */
class PageController extends Controller
{

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
     * @Route("/admin")
     * @Method({"GET"})
     * @Template()
     */
    public function adminAction()
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

        if (($errorList = $form->isValid()) == true) {
            $secret = $this->container->getParameter('recaptcha_secret');
            $recaptcha = new ReCaptcha($secret);
            $resp = $recaptcha->verify($request->get('g-recaptcha-response'), $request->getClientIp());
            if (!$resp->isSuccess()) {
                $this->get('session')->getFlashBag()->add('error', 'Echec de la validation reCAPTCHA');
                return $this->redirect($this->generateUrl('rtg_app_page_contact'));
            }
            $other_object = $form->get('other_object')->getData();
            if ($other_object == null || $form->get('object')->getData() != 'Autre') {
                $subject = $form->get('object')->getData();
            } else {
                $subject = $other_object;
            }
            $from = $this->container->getParameter('site_address');
            $to = '';
            if ($subject == 'Problème(s) à propos du Site') {
                $to = $this->container->getParameter('dev_delivery_address');
            } else {
                $to = $this->container->getParameter('contact_delivery_address');
            }
            $content = $form->get('message')->getData();

            $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom($from, 'Formulaire de contact RTG')
                    ->setReplyTo($form->get('email')->getData())
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
     * @Route("/forum")
     * @Method({"GET"})
     */
    public function forumAction()
    {
        return $this->redirect("http://forum.restartthegame.com");
    }

    /**
     * @Route("")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('RTGBlogBundle:NewsArticle')->getFeaturedArticles(5);
        $competitions = $em->getRepository('RTGBlogBundle:CompetitionArticle')->getLatestArticles(5);
        $port = $this->container->getParameter('chat_port');
        $domain = $this->getRequest()->getHost();
        $websocket_url = 'ws://' . $domain . ':' . $port;
        return array(
            'news' => $news,
            'competitions' => $competitions,
            'websocketUrl' => $websocket_url
        );
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
     * @Template("RTGAppBundle:Page:include/menu.html.twig")
     */
    public function menuAction($originalRequest)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('RTGBlogBundle:Category')->findAll();
        return array(
            'categories' => $categories,
            'request' => $originalRequest
        );
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
     * @Route("/search")
     * @Method({"GET"})
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $query = $request->query->get('query');
        $em = $this->getDoctrine()->getManager();
        if ($query) {
            $news = $em->getRepository('RTGBlogBundle:NewsArticle')->search($query, 6);
            $news_count = $em->getRepository('RTGBlogBundle:NewsArticle')->searchCount($query);
            $competitions = $em->getRepository('RTGBlogBundle:CompetitionArticle')->search($query, 6);
            $competitions_count = $em->getRepository('RTGBlogBundle:CompetitionArticle')->searchCount($query);
        } else {
            return array('query' => $query);
        }
        return array('query' => $query, 'news' => $news, 'news_count' => $news_count, 'competitions' => $competitions, 'competitions_count' => $competitions_count);
    }

    /**
     * @Route("/search/competition")
     * @Method({"GET"})
     * @Template("RTGAppBundle:Page:search.html.twig")
     */
    public function searchCompetitionAction(Request $request)
    {
        $query = $request->query->get('query');
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('RTGBlogBundle:CompetitionArticle')->search($query);
        return array('query' => $query, 'competitions' => $competitions, 'competitions_count' => count($competitions));
    }

    /**
     * @Route("/search/news")
     * @Method({"GET"})
     * @Template("RTGAppBundle:Page:search.html.twig")
     */
    public function searchNewsAction(Request $request)
    {
        $query = $request->query->get('query');
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('RTGBlogBundle:NewsArticle')->search($query);
        return array('query' => $query, 'news' => $news, 'news_count' => count($news));
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

}
