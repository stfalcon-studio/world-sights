<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Sight Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sights")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightRepository")
 *
 * @Gedmo\Loggable
 */
class Sight
{
    use TimestampableEntity;

    /**
     * @var int $id ID
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var SightType $sightType Sight type
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SightType", inversedBy="sights")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Versioned
     */
    private $sightType;

    /**
     * @var Locality $locality Locality
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Locality", inversedBy="sights")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Versioned
     */
    private $locality;

    /**
     * @var ArrayCollection|SightTour[] $sightTours Sight tours
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightTour", mappedBy="sight")
     */
    private $sightTours;

    /**
     * @var string $name Name
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="255")
     * @Assert\Type(type="string")
     *
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string $description Description
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var string $address Address
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $address;

    /**
     * @var string $phone Phone
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $phone;

    /**
     * @var string $website Website
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $website;

    /**
     * @var string $tags Tags
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $tags;

    /**
     * @var float $longitude Longitude
     *
     * @ORM\Column(type="float", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $longitude;

    /**
     * @var float $latitude Latitude
     *
     * @ORM\Column(type="float", nullable=true)
     *
     * @Gedmo\Versioned
     */
    private $latitude;

    /**
     * @var string $slug Slug
     *
     * @ORM\Column(type="string")
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
     * Constructor
     */
    public function __construct()
    {
        $this->sightTours = new ArrayCollection();
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New Sight';

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
     * Get sight type
     *
     * @return SightType Sight type
     */
    public function getSightType()
    {
        return $this->sightType;
    }

    /**
     * Set sight type
     *
     * @param SightType $sightType Sight type
     *
     * @return $this
     */
    public function setSightType($sightType)
    {
        $this->sightType = $sightType;

        return $this;
    }

    /**
     * Get locality
     *
     * @return Locality Locality
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set locality
     *
     * @param Locality $locality Locality
     *
     * @return $this
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

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
     * Get address
     *
     * @return string Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address
     *
     * @param string $address Address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone
     *
     * @param string $phone Phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get website
     *
     * @return string Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set website
     *
     * @param string $website Website
     *
     * @return $this
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string Tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param string $tags Tags
     *
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float Longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude Longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float Latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude Latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

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
        $this->slug = strtolower(str_replace(' ', '-', $slug));

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

    /**
     * Add sight tour
     *
     * @param SightTour $sightTour Sight tour
     *
     * @return Sight
     */
    public function addSightTour(SightTour $sightTour)
    {
        $this->sightTours[] = $sightTour;

        return $this;
    }

    /**
     * Remove sight tour
     *
     * @param SightTour $sightTour Sight tour
     */
    public function removeSightTour(SightTour $sightTour)
    {
        $this->sightTours->removeElement($sightTour);
    }

    /**
     * Set sight tours
     *
     * @param ArrayCollection|SightTour[] $sightTours Sight tours
     *
     * @return $this
     */
    public function setSightTours(ArrayCollection $sightTours)
    {
        foreach ($sightTours as $sightTour) {
            $sightTour->setSight($this);
        }
        $this->sightTours = $sightTours;

        return $this;
    }

    /**
     * Get sight tours
     *
     * @return ArrayCollection|SightTour[] Sight tours
     */
    public function getSightTours()
    {
        return $this->sightTours;
    }
}