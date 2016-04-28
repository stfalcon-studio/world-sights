<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Locality Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="localities")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocalityRepository")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class Locality
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
     * @JMS\Groups({"sight", "sight_ticket"})
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var Country $country Country
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="localities")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $country;

    /**
     * @var ArrayCollection|Sight[] $sights Sights
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sight", mappedBy="locality")
     */
    private $sights;

    /**
     * @var ArrayCollection|SightTicket[] $fromSightTickets From sights tickets
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightTicket", mappedBy="from")
     */
    private $fromSightTickets;

    /**
     * @var ArrayCollection|SightTicket[] $toSightTickets To sights tickets
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightTicket", mappedBy="to")
     */
    private $toSightTickets;

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
     * @JMS\Groups({"sight", "sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var boolean $enabled Enabled
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $enabled = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sights           = new ArrayCollection();
        $this->fromSightTickets = new ArrayCollection();
        $this->toSightTickets   = new ArrayCollection();
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New Locality';

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
     * Get country
     *
     * @return Country Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set country
     *
     * @param Country $country Country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
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
     * Is enabled?
     *
     * @return boolean Is enabled?
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled Enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

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
            $sight->setLocality($this);
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

    /**
     * Add from sight ticket
     *
     * @param SightTicket $fromSightTicket From sight ticket
     *
     * @return Locality
     */
    public function addFromSightTicket(SightTicket $fromSightTicket)
    {
        $this->fromSightTickets[] = $fromSightTicket;

        return $this;
    }

    /**
     * Remove from sight ticket
     *
     * @param SightTicket $fromSightTicket From sight ticket
     */
    public function removeFromSightTicket(SightTicket $fromSightTicket)
    {
        $this->fromSightTickets->removeElement($fromSightTicket);
    }

    /**
     * Set from sight tickets
     *
     * @param ArrayCollection|SightTicket[] $fromSightTickets From sight tickets
     *
     * @return $this
     */
    public function setFromSightTickets(ArrayCollection $fromSightTickets)
    {
        foreach ($fromSightTickets as $fromSightTicket) {
            $fromSightTicket->setFrom($this);
        }
        $this->fromSightTickets = $fromSightTickets;

        return $this;
    }

    /**
     * Get from sight tickets
     *
     * @return ArrayCollection|SightTicket[]
     */
    public function getFromSightTickets()
    {
        return $this->fromSightTickets;
    }

    /**
     * Add to sight ticket
     *
     * @param SightTicket $toSightTicket To sight ticket
     *
     * @return Locality
     */
    public function addToSightTicket(SightTicket $toSightTicket)
    {
        $this->toSightTickets[] = $toSightTicket;

        return $this;
    }

    /**
     * Remove to sight ticket
     *
     * @param SightTicket $toSightTicket To sight ticket
     */
    public function removeToSightTicket(SightTicket $toSightTicket)
    {
        $this->toSightTickets->removeElement($toSightTicket);
    }

    /**
     * Set to sight tickets
     *
     * @param ArrayCollection|SightTicket[] $toSightTickets To sight tickets
     *
     * @return $this
     */
    public function setToSightTickets(ArrayCollection $toSightTickets)
    {
        foreach ($toSightTickets as $toSightTicket) {
            $toSightTicket->setTo($this);
        }
        $this->toSightTickets = $toSightTickets;

        return $this;
    }

    /**
     * Get to sight tickets
     *
     * @return ArrayCollection|SightTicket[]
     */
    public function getToSightTickets()
    {
        return $this->toSightTickets;
    }
}
