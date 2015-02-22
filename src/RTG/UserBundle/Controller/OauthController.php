<?php

namespace RTG\UserBundle\Controller;

use GuzzleHttp\Exception\RequestException;
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
        $client = $this->get('rtg_app.twitchapiclient');
        try {
            $response = $client->get('/oauth2/authorize', ['query' => [
                'redirect_uri' => $this->generateUrl('rtg_user_oauth_checktwitch', array(), true),
                'response_type' => 'code',
                'scope' => implode('+', $scope)
            ]]);
        } catch (RequestException $e) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenu lors de l\'association au compte Twitch.');
            return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
        }
        return $this->redirect($response->getEffectiveUrl());
    }

    /**
     * @Route("/check/twitch")
     * @Method("GET")
     */
    public function checkTwitchAction(Request $request)
    {
        $client = $this->get('rtg_app.twitchapiclient');
        try {
            $response = $client->post('/oauth2/token', ['query' => [
                'client_secret' => $this->container->getParameter('twitch_client_secret'),
                'code' => $request->query->get('code'),
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->generateUrl('rtg_user_oauth_checktwitch', array(), true)
            ]]);
        } catch (RequestException $e) {
            $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenu lors de l\'association au compte Twitch.');
            return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
        }
        $data = json_decode((string) $response->getBody(), true);
        
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
