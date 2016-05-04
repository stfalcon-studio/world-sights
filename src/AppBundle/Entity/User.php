<?php

namespace AppBundle\Entity;

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
     * @JMS\Groups({"user"})
     * @JMS\Since("1.0")
     */
    protected $id;

    /**
     * @var string $username Username
     *
     * @JMS\Expose
     * @JMS\Groups({"user"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    protected $username;

    /**
     * @var string $email Email
     *
     * @JMS\Expose
     * @JMS\Groups({"user"})
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
}
