<?php

namespace RTG\AppBundle\Controller;

use GuzzleHttp\Exception\ClientException;
use RTG\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StreamController extends Controller
{

    /**
     * @Route("/tv")
     * @Method({"GET"})
     * @Template()
     */
    public function rtgTvAction()
    {
        $em = $this->getDoctrine()->getManager();
        $streamers = $em->getRepository('RTGUserBundle:User')->findStreamers();

        $channel_name = $host_target = $this->container->getParameter('twitch_channel');
        $client = $this->get('rtg_app.twitchapiwrapper');
        $channel = $client->getChannelByName($channel_name);

        $broadcasted_channel = $channel->getBroadcastedChannel();
        $stream = $client->getStream($broadcasted_channel->getName());
        return array('channel' => $channel, 'broadcastedChannel' => $broadcasted_channel, 'stream' => $stream, 'streamers' => $streamers);
    }
    
    /**
     * @ParamConverter("user", class="RTGUserBundle:User", options={"id" = "username", "repository_method" = "findOneByUsernameCanonical"})
     * @Route("/streamers/{username}")
     * @Method({"GET"})
     * @Template()
     */
    public function streamerAction(User $user)
    {
        if ($user->hasRole('ROLE_STREAMER') == false) {
            $message = $user->getUsername() . " n'est pas un streamer.";
            $this->get('session')->getFlashBag()->add('error', $message);
            return $this->redirect($this->generateUrl('rtg_app_stream_streamers'));
        }
        $parameters = array();
        if ($user->getTwitchAccessToken() != null) {
            $parameters['twitch'] = true;
        }

        if (empty($parameters)) {
            if ($this->getUser()->getId() == $user->getId()) {
                $message = 'Vous devez associer au moins un compte de streaming via la rubrique <a href="#my-connections" title="Mes connexions">Mes connexions</a>';
                $this->get('session')->getFlashBag()->add('error', $message);
                return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
            } else {
                $message = $user->getUsername() . " n'a pas autorisé de connection à une plateforme de streaming.";
                $this->get('session')->getFlashBag()->add('error', $message);
                return $this->redirect($this->generateUrl('rtg_app_stream_streamers'));
            }
        }
        
        $parameters['user'] = $user;

        return $parameters;
    }
    
     /**
     * @Route("/streamers")
     * @Method({"GET"})
     * @Template()
     */
    public function streamersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $streamers = $em->getRepository('RTGUserBundle:User')->findStreamers();
        return array(
            'streamers' => $streamers
        );
    }
    
    /**
     * @ParamConverter("user", class="RTGUserBundle:User", options={"id" = "username", "repository_method" = "findOneByUsernameCanonical"})
     * @Route("/streamers/{username}/twitch")
     * @Method({"GET"})
     * @Template()
     */
    public function twitchAction(User $user)
    {
        $client = $this->get('rtg_app.twitchapiwrapper');
        try {
            $channel = $client->getChannel($user);
        } catch (ClientException $e) {
            $twitch_oauth_url = $this->generateUrl('rtg_user_oauth_connecttwitch');
            $message = "Votre jeton d'authentification n'est plus valide veuillez"
                    . " de nouveau autoriser la connexion avec votre compte Twitch"
                    . " via le lien suivant: <a href=\"$twitch_oauth_url\" title=\"Autoriser la connexion"
                    . " à mon compte Twitch\">Autoriser la connexion à mon compte"
                    . " twitch</a>.";
            $this->get('session')->getFlashBag()->add('error', $message);
            return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
        }

        $broadcasted_channel = $channel->getBroadcastedChannel();
        $stream = $client->getStream($broadcasted_channel->getName());
        return array('channel' => $channel, 'broadcastedChannel' => $broadcasted_channel, 'stream' => $stream);
    }

}
