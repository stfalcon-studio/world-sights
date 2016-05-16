<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Sight Type Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sight_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightTypeRepository")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class SightType
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
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var ArrayCollection|Sight[] $sights Sights
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sight", mappedBy="sightType")
     */
    private $sights;

    /**
     * @var string $name Name
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sights = new ArrayCollection();
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New Sight Type';

        if (null !== $this->getName()) {
            $result = $this->getName();
        }

        return $result;
    }

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
     * Get name
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name Name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add sights
     *
     * @param Sight $sights Sights
     *
     * @return $this
     */
    public function addSight(Sight $sights)
    {
        $this->sights[] = $sights;

        return $this;
    }

    /**
     * Remove sights
     *
     * @param Sight $sights Sights
     *
     * @return $this
     */
    public function removeSight(Sight $sights)
    {
        $this->sights->removeElement($sights);

        return $this;
    }

    /**
     * Set sights
     *
     * @param ArrayCollection|Sight[] $sights Sights
     *
     * @return $this
     */
    public function setSights(ArrayCollection $sights)
    {
        foreach ($sights as $sight) {
            $sight->setSightType($this);
        }
        $this->sights = $sights;

        return $this;
    }

    /**
     * Get sights
     *
     * @return ArrayCollection|Sight[] Sights
     */
    public function getSights()
    {
        return $this->sights;
    }
}
