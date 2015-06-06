<?php

namespace RTG\AppBundle\Controller\Rest;

use RTG\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Session controller.
 *
 * @Route("/rest/users")
 */
class UserController extends Controller
{

    /**
     * @Route("/{id}/sessions")
     * @Method({"GET"})
     */
    public function sessionsAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $sessions = $em->getRepository("RTGAppBundle:Session")->getSessionEvents(
                $request->query->get('start'), $request->query->get('end'), $user
        );
        return new JsonResponse($sessions);
    }

}
