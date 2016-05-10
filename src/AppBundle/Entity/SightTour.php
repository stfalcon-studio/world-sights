<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Sight Tour Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sight_tours")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightTourRepository")
 * @UniqueEntity("slug")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class SightTour
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
     * @JMS\Groups({"sight", "sight_tour","sight_tour_for_sight"})
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var Sight $sight Sight
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sight", inversedBy="sightTours")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     * @JMS\Groups({"sight_tour"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $sight;

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
     * @JMS\Groups({"sight", "sight_tour", "sight_tour_for_sight"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string $companyName Company name
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_tour_for_sight"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $companyName;

    /**
     * @var string $companyLink Company Link
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_tour_for_sight"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $companyLink;

    /**
     * @var string $tourLink Tour Link
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_tour_for_sight"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $tourLink;

    /**
     * @var float $price Price
     *
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(type="numeric")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_tour_for_sight"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $price;

    /**
     * @var string $slug Slug
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_tour_for_sight"})
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
        $result = 'New Sight Tour';

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
     * Get company name
     *
     * @return string Company name
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set company name
     *
     * @param string $companyName Company name
     *
     * @return $this
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get company link
     *
     * @return string Company link
     */
    public function getCompanyLink()
    {
        return $this->companyLink;
    }

    /**
     * Set company link
     *
     * @param string $companyLink Company link
     *
     * @return $this
     */
    public function setCompanyLink($companyLink)
    {
        $this->companyLink = $companyLink;

        return $this;
    }

    /**
     * Get tour link
     *
     * @return string Tour link
     */
    public function getTourLink()
    {
        return $this->tourLink;
    }

    /**
     * Set tour link
     *
     * @param string $tourLink Tour link
     *
     * @return $this
     */
    public function setTourLink($tourLink)
    {
        $this->tourLink = $tourLink;

        return $this;
    }

    /**
     * Get price
     *
     * @return float Price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param float $price Price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

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
