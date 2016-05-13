<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Sight Review Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sight_reviews")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightReviewRepository")
 * @UniqueEntity("slug")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class SightReview
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
     * @var User $user User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sightReviews")
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
     * @var Sight $sight Sight
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sight", inversedBy="sightReviews")
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
     * @var string $name Name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $topic;

    /**
     * @var string $description Description
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var int $mark Mark
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1, max=5)
     *
     * @Gedmo\Versioned
     */
    private $mark;

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
     * Get topic
     *
     * @return string Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set topic
     *
     * @param string $topic Topic
     *
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get description
     *
     * @return string Description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description Description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get mark
     *
     * @return int Mark
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set mark
     *
     * @param int $mark Mark
     *
     * @return $this
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }
}
