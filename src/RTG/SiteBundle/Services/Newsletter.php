<?php

namespace RTG\SiteBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;

class NewsLetter
{

    public function __construct(EntityManagerInterface $em, Swift_Mailer $mailer, $newsletter_address, $noreply_address)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        self::$NEWSLETTER_EMAIL = $newsletter_address;
        self::$NOREPLY_EMAIL = $noreply_address;
    }
    
    /**
     * @param string $subject
     * @param string $content
     */
    public function send($subject, $content)
    {
        $users = $this->em->getRepository('RTGUserBundle:User')->getSuscribedToNewsletter();
        $to = array();
        foreach($users as $user) {
            $to[] = $user->getEmail();
        }

        if(count($to) > 0) {
            $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(self::$NEWSLETTER_EMAIL)
            ->setReturnPath(self::$NOREPLY_EMAIL)
            ->setBcc($to)
            ->setBody($content, 'text/html');
            $this->mailer->send($message);
        }
    }

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    /**
     * @var Swift_Mailer
     */
    protected $mailer;
    
    /**
     * @var string
     */
    public static $NEWSLETTER_EMAIL;
    
    /**
     * @var string
     */
    public static $NOREPLY_EMAIL;

}