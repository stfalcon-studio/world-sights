<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Sight Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sights")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightRepository")
 * @UniqueEntity("slug")
 *
 * @JMS\ExclusionPolicy("all")
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
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
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
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
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
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $locality;

    /**
     * @var ArrayCollection|SightTour[] $sightTours Sight tours
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightTour", mappedBy="sight")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour"})
     * @JMS\Since("1.0")
     */
    private $sightTours;

    /**
     * @var ArrayCollection|SightTicket[] $sightTickets Sight tickets
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightTicket", mappedBy="sight")
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour"})
     * @JMS\Since("1.0")
     */
    private $sightTickets;

    /**
     * @var ArrayCollection|SightVisit[] $sightVisits Sight visits
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightVisit", mappedBy="sight")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightVisits;

    /**
     * @var ArrayCollection|SightPhoto[] $sightPhotos Sight photos
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightPhoto", mappedBy="sight")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightPhotos;

    /**
     * @var ArrayCollection|SightReview[] $sightReviews Sight reviews
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightReview", mappedBy="sight")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightReviews;

    /**
     * @var ArrayCollection|SightRecommend[] $sightRecommends Sight recommends
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SightRecommend", mappedBy="sight")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $sightRecommends;

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
     * @var string $description Description
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var string $address Address
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $address;

    /**
     * @var string $phone Phone
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $phone;

    /**
     * @var string $website Website
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $website;

    /**
     * @var string $tags Tags
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $tags;

    /**
     * @var float $longitude Longitude
     *
     * @ORM\Column(type="float", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $longitude;

    /**
     * @var float $latitude Latitude
     *
     * @ORM\Column(type="float", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $latitude;

    /**
     * @var string $slug Slug
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @JMS\Expose
     * @JMS\Groups({"sight", "sight_tour", "sight_ticket", "sight_visits", "sight_visits_friends", "sight_photo", "sight_review", "sight_recommend"})
     * @JMS\Since("1.0")
     */
    private $slug;

    /**
     * @var bool $enabled Enabled
     *
     * @ORM\Column(type="boolean")
     *
     * @Gedmo\Versioned
     */
    private $enabled = true;

    /**
     * @var int $user ID of User
     *
     * @JMS\Expose
     * @JMS\Groups({"sight_visits_friends"})
     * @JMS\Since("1.0")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sightTours   = new ArrayCollection();
        $this->sightTickets = new ArrayCollection();
        $this->sightVisits  = new ArrayCollection();
        $this->sightPhotos  = new ArrayCollection();
        $this->sightReviews = new ArrayCollection();
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
     * @return bool Is enabled?
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled
     *
     * @param bool $enabled Enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

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

    /**
     * Add sight ticket
     *
     * @param SightTicket $sightTicket Sight ticket
     *
     * @return Sight
     */
    public function addSightTicket(SightTicket $sightTicket)
    {
        $this->sightTickets[] = $sightTicket;

        return $this;
    }

    /**
     * Remove sight ticket
     *
     * @param SightTicket $sightTicket Sight ticket
     */
    public function removeSightTicket(SightTicket $sightTicket)
    {
        $this->sightTours->removeElement($sightTicket);
    }

    /**
     * Set sight tickets
     *
     * @param ArrayCollection|SightTicket[] $sightTickets Sight tickets
     *
     * @return $this
     */
    public function setSightTickets(ArrayCollection $sightTickets)
    {
        foreach ($sightTickets as $sightTicket) {
            $sightTicket->setSight($this);
        }
        $this->sightTickets = $sightTickets;

        return $this;
    }

    /**
     * Get sight tickets
     *
     * @return ArrayCollection|SightTicket[] Sight tickets
     */
    public function getSightTickets()
    {
        return $this->sightTours;
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
            $sightVisit->setSight($this);
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
            $sightPhoto->setSight($this);
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
            $sightReview->setSight($this);
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
            $sightRecommend->setSight($this);
        }
        $this->sightRecommends = $sightRecommends;

        return $this;
    }
}
