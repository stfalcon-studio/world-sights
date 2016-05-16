<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Sight Visited
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sight_visits", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"sight_id", "user_id"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightVisitRepository")
 * @UniqueEntity(fields={"sight", "user"})
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class SightVisit
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
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var Sight $sight Sight
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sight", inversedBy="sightVisits")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $sight;

    /**
     * @var User $user User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sightVisits")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $user;

    /**
     * @var \Datetime $date Date
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="datetime")
     *
     * @Gedmo\Versioned
     */
    private $date;

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
     * Get sight
     *
     * @return Sight Sight
     */
    public function getSight()
    {
        return $this->sight;
    }

    /**
     * Set sight
     *
     * @param Sight $sight Sight
     *
     * @return $this
     */
    public function setSight($sight)
    {
        $this->sight = $sight;

        return $this;
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
     * Get date
     *
     * @return \Datetime Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \Datetime $date Date
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
