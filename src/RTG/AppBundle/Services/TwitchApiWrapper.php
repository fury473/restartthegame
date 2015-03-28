<?php

namespace RTG\AppBundle\Services;

use GuzzleHttp\ClientInterface;
use RTG\AppBundle\Model\Twitch\Channel;
use RTG\AppBundle\Model\Twitch\Stream;
use RTG\UserBundle\Entity\User;

class TwitchApiWrapper
{

    public function __construct(ClientInterface $client, ClientInterface $host_target_client)
    {
        $this->client = $client;
        $this->hostTargetClient = $host_target_client;
    }

    /**
     * @param User $user
     * @return Channel
     */
    public function getChannel(User $user)
    {
        $response = $this->client->get("/kraken/channel", ['headers' => ['Authorization' => 'OAuth ' . $user->getTwitchAccessToken()]]);
        $channel = new Channel((string) $response->getBody());
        $channel->setUser($user);
        $host_target = $this->getHostTarget($channel->getName());
        if ($host_target != null) {
            $hosted_channel = $this->getChannelByName($host_target, false);
            $channel->setHostedChannel($hosted_channel);
        }
        return $channel;
    }

    /**
     * @param string $name
     * @param boolean $getHostedChannel
     * @return Channel
     */
    public function getChannelByName($name, $getHostedChannel = true)
    {
        $response = $this->client->get("/kraken/channels/$name");
        $channel = new Channel((string) $response->getBody());
        if ($getHostedChannel) {
            $host_target = $this->getHostTarget($channel->getName());
            if ($host_target != null) {
                $hosted_channel = $this->getChannelByName($host_target, false);
                $channel->setHostedChannel($hosted_channel);
            }
        }
        return $channel;
    }

    public function getStream($channel_name)
    {
        $response = $this->client->get("/kraken/streams/$channel_name");
        $data = json_decode((string) $response->getBody());
        if ($data->stream == null) {
            return null;
        }
        return new Stream(json_encode($data->stream));
    }

    public function getHostTarget($channel_name)
    {
        $host_target = null;
        try {
            $response = $this->hostTargetClient->get("/rooms/$channel_name/host_target");
        } catch (RequestException $e) {
            
        }
        $data = json_decode((string) $response->getBody(), true);
        if (isset($data['host_target'])) {
            $host_target = $data['host_target'];
        }

        return $host_target;
    }

    /**
     * @var ClientInterface 
     */
    private $client;

    /**
     * @var ClientInterface 
     */
    private $hostTargetClient;

}
