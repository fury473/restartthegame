<?php

namespace RTG\AppBundle\Controller;

use GuzzleHttp\Exception\ClientException;
use RTG\UserBundle\Entity\User;
use RTG\AppBundle\Entity\Session;
use RTG\AppBundle\Form\SessionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Channel controller.
 *
 * @Route("/stream")
 */
class ChannelController extends Controller
{

    /**
     * @ParamConverter("user", class="RTGUserBundle:User", options={"id" = "username", "repository_method" = "findOneByUsernameCanonical"})
     * @Route("/{username}")
     * @Method({"GET"})
     * @Template()
     */
    public function channelsAction(User $user)
    {
        if ($user->hasRole('ROLE_STREAMER') == false) {
            throw $this->createNotFoundException();
        }
        $parameters = array();
        if ($user->getTwitchAccessToken() != null) {
            $parameters['twitch'] = true;
        }

        if (empty($parameters)) {
            $message = 'Vous devez associer au moins un compte de streaming via la rubrique <a href="#my-connections" title="Mes connexions">Mes connexions</a>';
            $this->get('session')->getFlashBag()->add('error', $message);
            return $this->redirect($this->generateUrl('rtg_user_user_myprofile'));
        }
        
        $parameters['user'] = $user;

        return $parameters;
    }
    
    /**
     * @ParamConverter("user", class="RTGUserBundle:User", options={"id" = "username", "repository_method" = "findOneByUsernameCanonical"})
     * @Route("/{username}/twitch")
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
