<?php

namespace RTG\AppBundle\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use RTG\AppBundle\Services\Chat;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IoServerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:ioserver:run')
            ->setDescription('Lance le serveur de chat.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getRepository('RTGAppBundle:ChatClient')->clear();
        $port = $this->getContainer()->getParameter('chat_port');
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->getContainer()->get('rtg_app.chat')
                )
            ), $port
        );

        $server->run();
    }

}
