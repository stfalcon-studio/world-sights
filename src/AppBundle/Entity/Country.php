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
 * Country Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryRepository")
 * @UniqueEntity("slug")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\Loggable
 */
class Country
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
     * @JMS\Groups({"sight", "sight_ticket", "sight_tour", "sight_ticket_for_sight", "sight_visits", "sight_visits_friends"})
     * @JMS\Since("1.0")
     */
    private $id;

    /**
     * @var ArrayCollection|Locality[] $localities Localities
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Locality", mappedBy="country")
     */
    private $localities;

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
     * @JMS\Groups({"sight", "sight_ticket", "sight_tour", "sight_ticket_for_sight", "sight_visits", "sight_visits_friends"})
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string $slug Slug
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     */
    private $slug;

    /**
     * @var bool $enabled Enabled
     *
     * @ORM\Column(type="bool")
     *
     * @Gedmo\Versioned
     */
    private $enabled = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->localities = new ArrayCollection();
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New Country';

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
     * Add localities
     *
     * @param Locality $localities Localities
     *
     * @return $this
     */
    public function addLocality(Locality $localities)
    {
        $this->localities[] = $localities;

        return $this;
    }

    /**
     * Remove localities
     *
     * @param Locality $localities Localities
     */
    public function removeLocality(Locality $localities)
    {
        $this->localities->removeElement($localities);
    }

    /**
     * Set localities
     *
     * @param ArrayCollection|Locality[] $localities Localities
     *
     * @return $this
     */
    public function setLocalities(ArrayCollection $localities)
    {
        foreach ($localities as $locality) {
            $locality->setCountry($this);
        }
        $this->localities = $localities;

        return $this;
    }

    /**
     * Get localities
     *
     * @return ArrayCollection|Locality[] Localities
     */
    public function getLocalities()
    {
        return $this->localities;
    }
}
