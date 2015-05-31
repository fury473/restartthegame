<?php

namespace RTG\AppBundle\Controller;

use RTG\AppBundle\Entity\ChatClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Chat controller.
 *
 * @Route("/chat")
 */
class ChatController extends Controller
{
    /**
     * @Route("/clients/{id}/popover")
     * @Method({"GET"})
     * @Template()
     */
    public function ajaxClientPopoverAction(ChatClient $client)
    {
        return array('client' => $client);
    }

}
