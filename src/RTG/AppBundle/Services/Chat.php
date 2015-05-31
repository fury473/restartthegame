<?php

namespace RTG\AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use RTG\AppBundle\Entity\ChatMessage;
use RTG\UserBundle\Entity\User;
use SplObjectStorage;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Chat implements MessageComponentInterface
{

    public function __construct(EntityManagerInterface $em, EngineInterface $templating)
    {
        $this->connections = new SplObjectStorage;
        $this->em = $em;
        $this->templating = $templating;
    }

    public function onOpen(ConnectionInterface $from)
    {
        $this->connections->attach($from);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);
        if (!isset($data->action)) {
            $from->close();
            return;
        }
        $this->em->clear();
        $user = null;
        $client = $this->em->getRepository('RTGAppBundle:ChatClient')->find($from->resourceId);
        if ($client != null) {
            $user = $client->getUser();
        } else {
            $user = $this->em->getRepository('RTGUserBundle:User')->find($data->user);
        }
        if ($user->getChatBanned()) {
            $frame = $this->createFrame("Vous avez été banni du chat.", null, false, 'banned');
            $from->send($frame['others']);
            return;
        }
        switch ($data->action) {
            case 'ban':
                $client = $this->em->getRepository('RTGAppBundle:ChatClient')->find($data->client_id);
                $target = $client->getUser();
                $target->setChatBanned(true);
                $this->em->persist($target);
                $this->em->flush();
                foreach($this->connections as $conn) {
                    if ($conn->resourceId == $client->getId()) {
                        $conn->close();
                        break;
                    }
                }
                break;
            case 'kick':
                foreach($this->connections as $conn) {
                    if ($conn->resourceId == $data->client_id) {
                        $conn->close();
                        break;
                    }
                }
                break;
            case 'new_client':
                $this->dispatchNewChatClient($from, $user);
                break;
            case 'new_message':
                $this->dispatchNewMessage($from, $user, $data->text);
                break;
            default:
                $from->close();
        }
    }

    public function onClose(ConnectionInterface $from)
    {
        $this->connections->detach($from);
        $client = $this->em->getRepository("RTGAppBundle:ChatClient")->find($from->resourceId);
        if ($client != null) {
            $this->em->getRepository("RTGAppBundle:ChatClient")->disconnect($client);
            $this->dispatchClientsList();
        }
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo $e->getMessage() . "\n";
    }
    
    /**
     * @param string $text
     * @param User $user
     * @param boolean $persist
     * @return string
     */
    private function createFrame($text, User $user = null, $persist = true, $action = 'new_message')
    {
        $message = new ChatMessage();
        $message->setText($text);
        $user_id = null;
        if ($user) {
            $message->setUser($user);
            $user_id = $user->getId();
        }
        if ($persist) {
            $this->em->persist($message);
            $this->em->flush();
        }
        $author_frame = array();
        $author_frame['action'] = $action;
        $author_frame['html'] = $this->templating->render('RTGAppBundle:Chat:message.html.twig', array('msg' => $message, 'app_user_id' => $user_id));
        $others_frame = array();
        $others_frame['action'] = $action;
        $others_frame['html'] = $this->templating->render('RTGAppBundle:Chat:message.html.twig', array('msg' => $message, 'app_user_id' => null));
        return array('author' => json_encode($author_frame), 'others' => json_encode($others_frame));
    }
    
    /**
     * @param array $messages
     * @param User $user
     * @return string
     */
    private function createFrameFromMessages(array $messages, User $user, $action = 'old_message')
    {
        $frame = array();
        $frame['action'] = $action;
        $frame['html'] = $this->templating->render('RTGAppBundle:Chat:messages.html.twig', array('messages' => $messages, 'app_user_id' => $user->getId()));
        return json_encode($frame);
    }

    private function dispatchClientsList()
    {
        $this->em->clear();
        $clients = $this->em->getRepository('RTGAppBundle:ChatClient')->findAll();
        $html = $this->templating->render('RTGAppBundle:Chat:clients.html.twig', array('clients' => $clients));
        $msg = json_encode(['action' => 'clients', 'html' => $html]);
        foreach ($this->connections as $conn) {
            $conn->send($msg);
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param User $user
     */
    private function dispatchNewChatClient(ConnectionInterface $from, User $user)
    {
        $last_messages = $this->em->getRepository("RTGAppBundle:ChatMessage")->getLastsMessages();
        $this->em->getRepository("RTGAppBundle:ChatClient")->connect($from->resourceId, $user);
        $this->dispatchClientsList();

        $announces = $this->em->getRepository("RTGAppBundle:ChatAnnounce")->findAll();
        foreach ($announces as $announce) {
            $text = str_replace("%username%", $user->getUsername(), $announce->getText());
            $frame = $this->createFrame($text, null, false);
            $from->send($frame['others']);
        }
        $frame_general = $this->createFrame($user->getUsername() . " s'est connecté.", null, false);
        foreach ($this->connections as $conn) {
            if ($from !== $conn) {
                $conn->send($frame_general['others']);
            }
        }
        $from->send($this->createFrameFromMessages($last_messages, $user));
        $this->em->clear();
    }

    /**
     * @param ConnectionInterface $from
     * @param User $user
     * @param string $text
     */
    private function dispatchNewMessage(ConnectionInterface $from, User $user, $text)
    {
        $frame = $this->createFrame($text, $user);
        foreach ($this->connections as $conn) {
            if ($conn !== $from) {
                $conn->send($frame['others']);
            } else {
                $conn->send($frame['author']);
            }
        }
        $this->em->clear();
    }
    
    /**
     * @var SplObjectStorage 
     */
    protected $connections;

    /**
     * @var EntityManagerInterface 
     */
    protected $em;

    /**
     * @var EngineInterface
     */
    protected $templating;

}
