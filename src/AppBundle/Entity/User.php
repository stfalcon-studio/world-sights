<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\FriendStatusType;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * User entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @UniqueEntity("password")
 * @UniqueEntity("accessToken")
 * @UniqueEntity("refreshToken")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class User extends BaseUser
{
    use TimestampableEntity;

    /**
     * @var int $id ID
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     * @JMS\Groups({"user", "friend", "sight_visits", "sight_photo", "sight_review"})
     * @JMS\Since("1.0")
     */
    protected $id;

    /**
     * @var ArrayCollection|Friend[] $userFriends User friends
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Friend", mappedBy="user")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $userFriends;

    /**
     * @var ArrayCollection|Friend[] $friendUsers Friend users
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Friend", mappedBy="friend")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $friendUsers;

    /**
     * @var ArrayCollection|SightVisit[] $sightVisits Sight visits
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightVisit", mappedBy="user")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightVisits;

    /**
     * @var ArrayCollection|SightReview[] $sightReviews Sight reviews
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightReview", mappedBy="user")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightReviews;

    /**
     * @var ArrayCollection|SightPhoto[] $sightPhotos Sight photos
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightPhoto", mappedBy="user")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightPhotos;

    /**
     * @var ArrayCollection|SightRecommend[] $sightRecommends Sight recommends
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightRecommend", mappedBy="user")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightRecommends;

    /**
     * @var string $username Username
     *
     * @JMS\Expose
     * @JMS\Groups({"user", "friend", "sight_visits", "sight_photo", "sight_review"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    protected $username;

    /**
     * @var string $email Email
     *
     * @JMS\Expose
     * @JMS\Groups({"user", "friend", "sight_visits", "sight_photo", "sight_review"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    protected $email;

    /**
     * @var string $accessToken Access token
     *
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"user"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $accessToken;

    /**
     * @var string $refreshToken Refresh token
     *
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"user"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $refreshToken;

    /**
     * @var \DateTime $expiredAt Expired At
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     *
     * @Gedmo\Versioned
     */
    private $expiredAt;

    /**
     * @var FriendStatusType $status Friend status type
     *
     * @JMS\Expose
     * @JMS\Groups({"friend"})
     * @JMS\Since("1.0")
     */
    private $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->roles = ['ROLE_USER'];
    }

    /**
     * Get id
     *
     * @return int ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get username
     *
     * @return string Username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username Username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get email
     *
     * @return string Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email Email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get access token
     *
     * @return string Access token
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set access token
     *
     * @param string $accessToken Access token
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get refreshToken
     *
     * @return string Refresh token
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set refresh token
     *
     * @param string $refreshToken Refresh token
     *
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get expired at
     *
     * @return \DateTime Expired at
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set expired at
     *
     * @param \DateTime $expiredAt Expired at
     *
     * @return $this
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Get status
     *
     * @return string Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param string $status Status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get salt
     *
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Add user friend
     *
     * @param Friend $userFriend User friend
     *
     * @return $this
     */
    public function addUserFriend(Friend $userFriend)
    {
        $this->userFriends[] = $userFriend;

        return $this;
    }

    /**
     * Remove user friend
     *
     * @param Friend $userFriend User friend
     */
    public function removeUserFriend(Friend $userFriend)
    {
        $this->userFriends->removeElement($userFriend);
    }

    /**
     * Set user friends
     *
     * @param ArrayCollection|Friend[] $userFriends User friends
     *
     * @return $this
     */
    public function setUserFriends(ArrayCollection $userFriends)
    {
        foreach ($userFriends as $userFriend) {
            $userFriend->setUser($this);
        }
        $this->userFriends = $userFriends;

        return $this;
    }

    /**
     * Get user friends
     *
     * @return ArrayCollection|Friend[] User friends
     */
    public function getUserFriends()
    {
        return $this->userFriends;
    }

    /**
     * Add friend user
     *
     * @param Friend $friendUser Friend user
     *
     * @return User
     */
    public function addFriendUser(Friend $friendUser)
    {
        $this->friendUsers[] = $friendUser;

        return $this;
    }

    /**
     * Remove friend user
     *
     * @param Friend $friendUser Friend user
     */
    public function removeFriendUser(Friend $friendUser)
    {
        $this->friendUsers->removeElement($friendUser);
    }

    /**
     * Set friend users
     *
     * @param ArrayCollection|Friend[] $friendUsers Friend users
     *
     * @return $this
     */
    public function setFriendUsers(ArrayCollection $friendUsers)
    {
        foreach ($friendUsers as $friendUser) {
            $friendUser->setUser($this);
        }
        $this->friendUsers = $friendUsers;

        return $this;
    }

    /**
     * Get friend users
     *
     * @return ArrayCollection|Friend[] Friend users
     */
    public function getFriendUsers()
    {
        return $this->friendUsers;
    }

    /**
     * Add sight visit
     *
     * @param SightVisit $sightVisit Sight visit
     *
     * @return $this
     */
    public function addSightVisit(SightVisit $sightVisit)
    {
        $this->sightVisits[] = $sightVisit;

        return $this;
    }

    /**
     * Remove sight visit
     *
     * @param SightVisit $sightVisit Sight visit
     */
    public function removeSightVisit(SightVisit $sightVisit)
    {
        $this->sightVisits->removeElement($sightVisit);
    }

    /**
     * Get sight visits
     *
     * @return ArrayCollection|SightVisit[] Sight visits
     */
    public function getSightVisits()
    {
        return $this->sightVisits;
    }

    /**
     * Set sight visits
     *
     * @param ArrayCollection|SightVisit[] $sightVisits Sight visits
     *
     * @return $this
     */
    public function setSightVisits(ArrayCollection $sightVisits)
    {
        foreach ($sightVisits as $sightVisit) {
            $sightVisit->setUser($this);
        }
        $this->sightVisits = $sightVisits;

        return $this;
    }

    /**
     * Add sight photo
     *
     * @param SightPhoto $sightPhoto Sight photo
     *
     * @return $this
     */
    public function addSightPhoto(SightPhoto $sightPhoto)
    {
        $this->sightPhotos[] = $sightPhoto;

        return $this;
    }

    /**
     * Remove sight photo
     *
     * @param SightPhoto $sightPhoto Sight photo
     */
    public function removeSightPhoto(SightPhoto $sightPhoto)
    {
        $this->sightPhotos->removeElement($sightPhoto);
    }

    /**
     * Get sight photos
     *
     * @return ArrayCollection|SightPhoto[] Sight photos
     */
    public function getSightPhotos()
    {
        return $this->sightPhotos;
    }

    /**
     * Set sight photos
     *
     * @param ArrayCollection|SightPhoto[] $sightPhotos Sight photos
     *
     * @return $this
     */
    public function setSightPhotos(ArrayCollection $sightPhotos)
    {
        foreach ($sightPhotos as $sightPhoto) {
            $sightPhoto->setUser($this);
        }
        $this->sightPhotos = $sightPhotos;

        return $this;
    }

    /**
     * Add sight review
     *
     * @param SightReview $sightReview Sight review
     *
     * @return $this
     */
    public function addSightReview(SightReview $sightReview)
    {
        $this->sightReviews[] = $sightReview;

        return $this;
    }

    /**
     * Remove sight review
     *
     * @param SightReview $sightReview Sight review
     */
    public function removeSightReview(SightReview $sightReview)
    {
        $this->sightReviews->removeElement($sightReview);
    }

    /**
     * Get sight reviews
     *
     * @return ArrayCollection|SightReview[] Sight reviews
     */
    public function getSightReviews()
    {
        return $this->sightReviews;
    }

    /**
     * Set sight reviews
     *
     * @param ArrayCollection|SightReview[] $sightReviews Sight reviews
     *
     * @return $this
     */
    public function setSightReviews(ArrayCollection $sightReviews)
    {
        foreach ($sightReviews as $sightReview) {
            $sightReview->setUser($this);
        }
        $this->sightReviews = $sightReviews;

        return $this;
    }

    /**
     * Add sight recommend
     *
     * @param SightRecommend $sightRecommend Sight recommend
     *
     * @return $this
     */
    public function addSightRecommend(SightRecommend $sightRecommend)
    {
        $this->sightRecommends[] = $sightRecommend;

        return $this;
    }

    /**
     * Remove sight recommend
     *
     * @param SightRecommend $sightRecommend Sight recommend
     */
    public function removeSightRecommend(SightRecommend $sightRecommend)
    {
        $this->sightRecommends->removeElement($sightRecommend);
    }

    /**
     * Get sight recommends
     *
     * @return ArrayCollection|SightRecommend[] Sight recommends
     */
    public function getSightRecommends()
    {
        return $this->sightRecommends;
    }

    /**
     * Set sight recommends
     *
     * @param ArrayCollection|SightRecommend[] $sightRecommends Sight recommends
     *
     * @return $this
     */
    public function setSightRecommends(ArrayCollection $sightRecommends)
    {
        foreach ($sightRecommends as $sightRecommend) {
            $sightRecommend->setUser($this);
        }
        $this->sightRecommends = $sightRecommends;

        return $this;
    }
}
