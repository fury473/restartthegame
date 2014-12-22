<?php

namespace RTG\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 * @Route("/oauth")
 */
class OAuthController extends Controller
{

    /**
     * @Route("/connect/twitch")
     * @Method("GET")
     */
    public function connectTwitchAction()
    {
        $scope = array('channel_read');
        $url = 'https://api.twitch.tv/kraken/oauth2/authorize' .
                '?response_type=code' .
                '&client_id=' . $this->container->getParameter('twitch_client_id') .
                '&redirect_uri=' . $this->generateUrl('rtg_user_oauth_checktwitch', array(), true) .
                '&scope=' . implode('+', $scope);
        return $this->redirect($url);
    }

    /**
     * @Route("/check/twitch")
     * @Method("GET")
     */
    public function checkTwitchAction(Request $request)
    {
        $url = 'https://api.twitch.tv/kraken/oauth2/token';
        $content = 'client_id=' . $this->container->getParameter('twitch_client_id') .
                '&client_secret=' . $this->container->getParameter('twitch_client_secret') .
                '&grant_type=authorization_code' .
                '&redirect_uri=' . $this->generateUrl('rtg_user_oauth_checktwitch', array(), true) .
                '&code=' . $request->query->get('code');
        $buzz = $this->container->get('buzz');
        $buzz->getClient()->setVerifyPeer(false);
        $response = $buzz->post($url, array(), $content);
        $data = json_decode($response->getContent(), true);
        
        $user = $this->getUser();
        $user->setTwitchAccessToken($data['access_token']);
        $user->setTwitchRefreshToken($data['refresh_token']);
        $user->setTwitchScopes($data['scope']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
    }
    
    /**
     * @Route("/disconnect/twitch")
     * @Method("GET")
     */
    public function disconnectTwitchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->resetTwitchAccess();
        $em->persist($user);
        $em->flush();
        return $this->redirect($request->get('path'));
    }

}
