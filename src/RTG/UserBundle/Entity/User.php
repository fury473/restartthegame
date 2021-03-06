<?php

namespace RTG\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use RTG\AppBundle\Entity\Session;
use RTG\BlogBundle\Entity\NewsArticle;
use RTG\BlogBundle\Entity\NewsComment;
use RTG\BlogBundle\Entity\CompetitionArticle;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="RTG\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    public function __construct()
    {
        parent::__construct();
        $this->newsletter = false;
        $this->chatBanned = false;
        $this->generateNewsletterToken();
        $this->comments = new ArrayCollection();
        $this->competitions = new ArrayCollection();
    }
    
    /**
     * @param NewsArticle $articles
     * @return User
     */
    public function addArticle(NewsArticle $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }
    
    /**
     * @param NewsComment $comments
     * @return User
     */
    public function addComment(\RTG\BlogBundle\Entity\NewsComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }
    
    /**
     * @param CompetitionArticle $competition
     * @return User
     */
    public function addCompetition(CompetitionArticle $competition)
    {
        $this->competitions[] = $competition;

        return $this;
    }
    
    /**
     * @param Session $session
     * @return User
     */
    public function addSession(Session $session)
    {
        $this->sessions[] = $session;

        return $this;
    }
    
    /**
     * @return User
     */
    public function generateNewsletterToken()
    {
        $this->newsletterToken = md5(uniqid());
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAge()
    {
        if ($this->birthday == null) {
            return null;
        } else {
            $now = new \DateTime();
            $interval = $now->diff($this->birthday);
            if ($interval->y > 0) {
                return $interval->y . ' ans';
            } elseif ($interval->m > 0) {
                return $interval->m . ' mois';
            } elseif ($interval->d > 0) {
                return $interval->d . ' jours';
            }
        }
    }
    
    /**
     * @return Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
    
    /**
     * @return Avatar 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    /**
     * @return DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    /**
     * @return boolean 
     */
    public function getChatBanned()
    {
        return $this->chatBanned;
    }
    
    /**
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * @return Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * @return Collection
     */
    public function getCompetitions()
    {
        return $this->competitions;
    }
    
    /**
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * @return string 
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }
    
    /**
     * @return boolean
     */
    public function getNewsletterToken()
    {
        return $this->newsletterToken;
    }

    /**
     * @return string
     */
    public function getRank()
    {
        if ($this->hasRole('ROLE_SUPER_ADMIN')) {
            return 'Super Admin';
        } elseif ($this->hasRole('ROLE_STAFF')) {
            return 'Staff RTG';
        } else {
            return 'Membre';
        }
    }
    
    /**
     * @return Collection 
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * @return string
     */
    public function getTwitchAccessToken()
    {
        return $this->twitchAccessToken;
    }

    /**
     * @return string
     */
    public function getTwitchRefreshToken()
    {
        return $this->twitchRefreshToken;
    }

    /**
     * @return array
     */
    public function getTwitchScopes()
    {
        return unserialize($this->twitchScopes);
    }
    
    /**
     * @param Avatar $avatar
     * @return User
     */
    public function setAvatar(Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @param Date $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }
    
    /**
     * @param boolean $chat_banned
     * @return User 
     */
    public function setChatBanned($chat_banned)
    {
        $this->chatBanned = $chat_banned;
        return $this;
    }

    /**
     * @param string $city
     * @return User 
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    
    /**
     * @param string $first_name
     * @return User 
     */
    public function setFirstName($first_name)
    {
        $this->firstName = $first_name;
        return $this;
    }
    
    /**
     * @param string $function
     * @return User 
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }
    
    /**
     * @param string $last_name
     * @return User 
     */
    public function setLastName($last_name)
    {
        $this->lastName = $last_name;
        return $this;
    }

    /**
     * @param boolean $newsletter
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
        return $this;
    }
    
    /**
     * @param string $newsletter_token
     * @return User
     */
    public function setNewsletterToken($newsletter_token)
    {
        $this->newsletterToken = $newsletter_token;
        return $this;
    }
    
    /**
     * @param NewsArticle $article
     * @return User
     */
    public function removeArticle(NewsArticle $article)
    {
        $this->articles->removeElement($article);
        return $this;
    }
    
    /**
     * @param NewsComment $comment
     * @return User
     */
    public function removeComment(NewsComment $comment)
    {
        $this->articles->removeElement($comment);
        return $this;
    }
    
    /**
     * @param CompetitionArticle $competition
     * @return User
     */
    public function removeCompetition(CompetitionArticle $competition)
    {
        $this->competitions->removeElement($competition);
        return $this;
    }
    
    /**
     * @param Session $session
     * @return User
     */
    public function removeSession(Session $session)
    {
        $this->sessions->removeElement($session);
        return $this;
    }
    
    /**
     * Reset twitch columns
     * @return User
     */
    public function resetTwitchAccess()
    {
        $this->setTwitchAccessToken(null);
        $this->setTwitchRefreshToken(null);
        $this->setTwitchScopes(null);
        return $this;
    }

    /**
     * @param string $twitchAccessToken
     * @return User
     */
    public function setTwitchAccessToken($twitchAccessToken)
    {
        $this->twitchAccessToken = $twitchAccessToken;
        return $this;
    }

    /**
     * @param string $twitchRefreshToken
     * @return User
     */
    public function setTwitchRefreshToken($twitchRefreshToken)
    {
        $this->twitchRefreshToken = $twitchRefreshToken;
        return $this;
    }

    /**
     * @param array $twitchScopes
     * @return User
     */
    public function setTwitchScopes(array $twitchScopes = null)
    {
        if ($twitchScopes != null) {
            $this->twitchScopes = serialize($twitchScopes);
        } else {
            $this->twitchScopes = null;
        }
        return $this;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToMany(targetEntity="RTG\BlogBundle\Entity\NewsArticle", mappedBy="author")
     */
    protected $articles;

    /**
     * @ORM\OneToOne(targetEntity="RTG\UserBundle\Entity\Avatar", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $avatar;

    /**
     * @ORM\Column(type="date", name="birthday", nullable=true)
     */
    protected $birthday;
    
    /**
     * @ORM\Column(type="boolean", name="chat_banned")
     */
    protected $chatBanned;

    /**
     * @ORM\Column(type="string", name="city", nullable=true)
     */
    protected $city;

    /**
     * @ORM\OneToMany(targetEntity="RTG\BlogBundle\Entity\NewsComment", mappedBy="user")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="RTG\BlogBundle\Entity\CompetitionArticle", mappedBy="users")
     * */
    protected $competitions;
    
    /**
     * @ORM\Column(type="string", name="first_name", nullable=true)
     */
    protected $firstName;
    
    /**
     * @ORM\Column(type="string", name="function", nullable=true)
     */
    protected $function;
    
    /**
     * @ORM\Column(type="string", name="last_name", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="boolean", name="newsletter")
     */
    protected $newsletter;

    /**
     * @ORM\Column(type="string", name="newsletter_token", nullable=true)
     */
    protected $newsletterToken;
    
    /**
     * @ORM\OneToMany(targetEntity="RTG\AppBundle\Entity\Session", mappedBy="user")
     */
    protected $sessions;

    /**
     * @ORM\Column(type="string", name="twitch_access_token", nullable=true)
     */
    protected $twitchAccessToken;

    /**
     * @ORM\Column(type="string", name="twitch_refresh_token", nullable=true)
     */
    protected $twitchRefreshToken;

    /**
     * @ORM\Column(type="string", name="twitch_scopes", nullable=true)
     */
    protected $twitchScopes;

}
