<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\FriendStatusType;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Friend Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="friends")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FriendRepository")
 * @ORM\EntityListeners({"AppBundle\EntityListener\FriendListener"})
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class Friend
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
     * @JMS\Groups({"friend"})
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var User $user User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userFriends")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"friend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $user;

    /**
     * @var User $friend Friend
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="friendUsers")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"friend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $friend;

    /**
     * @var string $status Friend status type
     *
     * @ORM\Column(name="status", type="FriendStatusType", nullable=false)
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\FriendStatusType")
     *
     * @JMS\Expose
     * @JMS\Groups({"friend"})
     * @JMS\Since("1.0")
     */
    private $status = FriendStatusType::SENT;

    /**
     * Get ID
     *
     * @return int ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user
     *
     * @return User User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user User
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get friend
     *
     * @return User Friend
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * Set friend
     *
     * @param User $friend Friend
     *
     * @return $this
     */
    public function setFriend($friend)
    {
        $this->friend = $friend;

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
}
