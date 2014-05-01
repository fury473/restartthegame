<?php

namespace RTG\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function sidebarAction(Request $request)
    {
        $type = $request->get('type', null);
        
        $em = $this->getDoctrine()->getManager();
        
        $commentLimit   = $this->container->getParameter('rtg_blog.comments.latest_comment_limit');
        
        if($type == 'news') {
            $latestComments = $em->getRepository('RTGBlogBundle:NewsComment')->getLatestComments($commentLimit);
        } elseif($type = 'competition') {
        $latestComments = $em->getRepository('RTGBlogBundle:CompetitionComment')->getLatestComments($commentLimit);
        }
        
        return $this->render('RTGBlogBundle:Page:sidebar.html.twig', array(
            'latestComments'    => $latestComments,
            'type' => $type,
        ));
    }
}
