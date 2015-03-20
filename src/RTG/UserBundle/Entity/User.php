<?php

namespace RTG\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use RTG\AppBundle\Entity\Stream;
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
     * @param Stream $stream
     * @return User
     */
    public function addStream(Stream $stream)
    {
        $this->streams[] = $stream;

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
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
    public function getStreams()
    {
        return $this->streams;
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
     * @param string $city
     * @return User 
     */
    public function setCity($city)
    {
        $this->city = $city;
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
     * @param Stream $stream
     * @return User
     */
    public function removeStream(Stream $stream)
    {
        $this->streams->removeElement($stream);
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
     * @ORM\Column(type="boolean", name="newsletter")
     */
    protected $newsletter;

    /**
     * @ORM\Column(type="string", name="newsletter_token", nullable=true)
     */
    protected $newsletterToken;
    
    /**
     * @ORM\OneToMany(targetEntity="RTG\AppBundle\Entity\Stream", mappedBy="user")
     */
    protected $streams;

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
