<?php

namespace RTG\AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class NewsLetter
{

    public function __construct(EntityManagerInterface $em, Swift_Mailer $mailer, EngineInterface $templating, $newsletter_address, $noreply_address)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
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
        foreach($users as $user) {
            $templated_content = $this->templating->render('RTGBlogBundle:Newsletter:mail.html.twig', array('user' => $user, 'content' => $content));
            $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array(self::$NEWSLETTER_EMAIL => 'Restart The Game'))
            ->setReturnPath(self::$NOREPLY_EMAIL)
            ->setTo($user->getEmail())
            ->setBody($templated_content, 'text/html');
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
     * @var EngineInterface
     */
    protected $templating;
    
    /**
     * @var string
     */
    public static $NEWSLETTER_EMAIL;
    
    /**
     * @var string
     */
    public static $NOREPLY_EMAIL;

}