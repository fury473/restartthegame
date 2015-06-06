<?php

namespace RTG\AppBundle\Controller\Rest;

use DateTime;
use RTG\AppBundle\Entity\Session;
use RTG\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Session controller.
 *
 * @Route("/rest/sessions")
 */
class SessionController extends Controller
{

    /**
     * @Route("")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $params = $request->request->get('session');
        $em = $this->getDoctrine()->getManager();
        $sc = $this->get('security.context');
        $user = $em->getRepository("RTGUserBundle:User")->find($params['user_id']);
        if ($sc->getToken()->getUser() != $user && !$sc->isGranted('ROLE_ADMIN')) {
            throw new HttpException(403);
        }
        $session = new Session();
        $startAt = DateTime::createFromFormat('d/m/Y H:i', $params['startAt']);
        $endAt = DateTime::createFromFormat('d/m/Y H:i', $params['endAt']);
        $session->setStartAt($startAt);
        $session->setEndAt($endAt);
        $session->setTitle($params['title']);
        $session->setUser($user);
        $em->persist($session);
        $em->flush();

        return new JsonResponse($session->toEvent());
    }
    
    /**
     * @Route("/{id}")
     * @Method({"DELETE"})
     */
    public function deleteAction(Session $session)
    {
        $sc = $this->get('security.context');
        if ($sc->getToken()->getUser() != $session->getUser() && !$sc->isGranted('ROLE_ADMIN')) {
            throw new HttpException(403);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($session);
        $em->flush();
        return new JsonResponse();
    }

    /**
     * @Route("")
     * @Method({"GET"})
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sessions = $em->getRepository("RTGAppBundle:Session")->getSessionEvents(
                $request->query->get('start'), $request->query->get('end')
        );
        foreach ($sessions as &$session) {
            $session['url'] = $this->generateUrl('rtg_app_stream_streamer', array('username' => $session['organizer']));
        }
        return new JsonResponse($sessions);
    }
    
    /**
     * @Route("/{id}")
     * @Method({"PUT"})
     */
    public function updateAction(Request $request, Session $session)
    {
        $sc = $this->get('security.context');
        if ($sc->getToken()->getUser() != $session->getUser() && !$sc->isGranted('ROLE_ADMIN')) {
            throw new HttpException(403);
        }
        $em = $this->getDoctrine()->getManager();
        $move_delta = $request->request->get('move_delta');
        $resize_delta = $request->request->get('resize_delta');
        if ($move_delta != null) {
            $start_date = $this->getNewDate($session->getStartAt(), $move_delta);
            $end_date = $this->getNewDate($session->getEndAt(), $move_delta);
            $session->setStartAt($start_date);
            $session->setEndAt($end_date);
        }
        if ($resize_delta != null) {
            $date = $this->getNewDate($session->getEndAt(), $resize_delta);
            if($date < $session->getStartAt()) {
                $date = clone $session->getStartAt();
            }
            $session->setEndAt($date);
        }
        $em->flush();
        return new JsonResponse($session->toEvent());
    }

    /**
     * @param DateTime $date
     * @param array $delta
     * @return DateTime
     */
    private function getNewDate($date, $delta) {
        $new_date = clone $date;
        $new_date->modify($delta['years'].' years');
        $new_date->modify($delta['months'].' months');
        $new_date->modify($delta['days'].' days');
        $new_date->modify($delta['hours'].' hours');
        $new_date->modify($delta['minutes'].' minutes');
        $new_date->modify($delta['seconds'].' seconds');
        return $new_date;
    }

}
