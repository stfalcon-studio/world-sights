<?php

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\SightTicketType;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Sight Ticket Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sight_tickets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightTicketRepository")
 * @UniqueEntity("slug")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class SightTicket
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
     * @JMS\Groups({"sight", "sight_ticket_for_sight", "sight_tour", "sight_ticket"})
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var Sight $sight Sight
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sight", inversedBy="sightTickets")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $sight;

    /**
     * @var Locality $from From locality
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locality", inversedBy="fromSightTickets")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_ticket_for_sight", "sight_tour", "sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $from;

    /**
     * @var Locality $to To locality
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locality", inversedBy="toSightTickets")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_ticket_for_sight", "sight_tour", "sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $to;

    /**
     * @var SightTicketType $type Type
     *
     * @ORM\Column(name="type", type="SightTicketType", nullable=false)
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\SightTicketType")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_ticket_for_sight", "sight_tour", "sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var string $linkBuy Link to buy ticket
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_ticket_for_sight", "sight_tour", "sight_ticket"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $linkBuy;

    /**
     * @var string $slug Slug
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_ticket_for_sight", "sight_tour", "sight_ticket"})
     * @JMS\Since("1.0")
     */
    private $slug;

    /**
     * @var boolean $enabled Enabled
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $enabled = true;

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New Sight Ticket';

        if (null !== $this->getFrom()->getName() && null != $this->getTo()->getName()) {
            $result = 'Ticket from: '.$this->getFrom()->getName().'; to: '.$this->getTo()->getName();
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
     * Get from
     *
     * @return Locality From locality
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set from
     *
     * @param Locality $from From locality
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get to
     *
     * @return Locality To locality
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set to
     *
     * @param Locality $to To locality
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get type
     *
     * @return SightTicketType Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type Type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get link buy
     *
     * @return string Link to buy ticket
     */
    public function getLinkBuy()
    {
        return $this->linkBuy;
    }

    /**
     * Set link buy
     *
     * @param string $linkBuy link to buy ticket
     *
     * @return $this
     */
    public function setLinkBuy($linkBuy)
    {
        $this->linkBuy = $linkBuy;

        return $this;
    }

    /**
     * Set slug
     *
     * @param string $slug Slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string Slug
     */
    public function getSlug()
    {
        return $this->slug;
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
}
