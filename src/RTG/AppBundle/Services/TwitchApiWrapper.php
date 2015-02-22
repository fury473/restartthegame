<?php

namespace RTG\AppBundle\Services;

use GuzzleHttp\ClientInterface;

class TwitchApiWrapper
{

    public function __construct(ClientInterface $client, ClientInterface $host_target_client)
    {
        $this->client = $client;
        $this->hostTargetClient = $host_target_client;
    }
    
    public function getChannel($channel) {
        $response = $this->client->get("/kraken/channels/$channel");
        $data = json_decode((string) $response->getBody(), true);
        $data['host_target'] = $this->getHostTarget($channel);
        return $data;
    }
    
    public function getStream($channel) {
        $response = $this->client->get("/kraken/streams/$channel");
        $data = json_decode((string) $response->getBody(), true);
        return $data;
    }
    
    public function getHostTarget($channel)
    {
        $host_target = null;
        try {
            $response = $this->hostTargetClient->get("/rooms/$channel/host_target");
        } catch (RequestException $e) {
        }
        $data = json_decode((string) $response->getBody(), true);
        if(isset($data['host_target'])) {
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
