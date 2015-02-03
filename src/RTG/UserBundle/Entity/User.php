<?php

namespace RTG\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use RTG\BlogBundle\Entity\NewsArticle;
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set birthday
     *
     * @var \Date
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @var string
     * @return User 
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Compute age
     *
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
     * Add articles
     *
     * @param RTG\BlogBundle\Entity\NewsArticle $articles
     * @return User
     */
    public function addArticle(NewsArticle $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param RTG\BlogBundle\Entity\NewsArticle $articles
     */
    public function removeArticle(NewsArticle $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get competitions
     * @return ArrayCollection
     */
    public function getCompetitions()
    {
        return $this->competitions;
    }

    /**
     * Add competition
     *
     * @param RTG\BlogBundle\Entity\CompetitionArticle $competition
     * @return User
     */
    public function addCompetition(CompetitionArticle $competition)
    {
        $this->competitions[] = $competition;

        return $this;
    }

    /**
     * Remove competition
     *
     * @param RTG\BlogBundle\Entity\CompetitionArticle $competition
     */
    public function removeCompetition(CompetitionArticle $competition)
    {
        $this->competitions->removeElement($competition);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add comments
     *
     * @param \RTG\BlogBundle\Entity\NewsComment $comments
     * @return User
     */
    public function addComment(\RTG\BlogBundle\Entity\NewsComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \RTG\BlogBundle\Entity\NewsComment $comment
     */
    public function removeComment(\RTG\BlogBundle\Entity\NewsComment $comment)
    {
        $this->articles->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set avatar
     *
     * @param \RTG\UserBundle\Entity\Avatar $avatar
     * @return User
     */
    public function setAvatar(\RTG\UserBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \RTG\UserBundle\Entity\Avatar 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }
    
    /**
     * Get newsletterToken
     *
     * @return boolean
     */
    public function getNewsletterToken()
    {
        return $this->newsletterToken;
    }

    /**
     * Get rank
     * 
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
     * Set newsletter
     *
     * @return \RTG\UserBundle\Entity\User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
        return $this;
    }
    
    /**
     * Set newsletterToken
     *
     * @param string $newsletter_token
     * @return \RTG\UserBundle\Entity\User
     */
    public function setNewsletterToken($newsletter_token)
    {
        $this->newsletterToken = $newsletter_token;
        return $this;
    }

    /**
     * Get twitchAccessToken
     * 
     * @return string
     */
    public function getTwitchAccessToken()
    {
        return $this->twitchAccessToken;
    }

    /**
     * Get twitchRefreshToken
     * 
     * @return string
     */
    public function getTwitchRefreshToken()
    {
        return $this->twitchRefreshToken;
    }

    /**
     * Get twitchScopes
     * 
     * @return array
     */
    public function getTwitchScopes()
    {
        return unserialize($this->twitchScopes);
    }
    
    /**
     * Generate newsletterToken
     * 
     * @return \RTG\UserBundle\Entity\User
     */
    public function generateNewsletterToken()
    {
        $this->newsletterToken = md5(uniqid());
        return $this;
    }

    /**
     * Reset twitch columns
     * 
     * @return \RTG\UserBundle\Entity\User
     */
    public function resetTwitchAccess()
    {
        $this->setTwitchAccessToken(null);
        $this->setTwitchRefreshToken(null);
        $this->setTwitchScopes(null);
        return $this;
    }

    public function setTwitchAccessToken($twitchAccessToken)
    {
        $this->twitchAccessToken = $twitchAccessToken;
        return $this;
    }

    public function setTwitchRefreshToken($twitchRefreshToken)
    {
        $this->twitchRefreshToken = $twitchRefreshToken;
        return $this;
    }

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
     * @ORM\Column(type="date", name="birthday", nullable=true)
     */
    protected $birthday;

    /**
     * @ORM\Column(type="string", name="city", nullable=true)
     */
    protected $city;

    /**
     * @ORM\OneToOne(targetEntity="RTG\UserBundle\Entity\Avatar", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $avatar;

    /**
     * @ORM\OneToMany(targetEntity="RTG\BlogBundle\Entity\NewsArticle", mappedBy="author")
     */
    protected $articles;

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
