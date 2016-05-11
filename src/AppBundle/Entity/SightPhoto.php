<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Sight Photo Entity
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @ORM\Table(name="sight_photos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SightPhotoRepository")
 *
 * @JMS\ExclusionPolicy("all")
 *
 * @Vich\Uploadable
 *
 * @Gedmo\Loggable
 */
class SightPhoto
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sight", inversedBy="sightPhotos")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sightPhotos")
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
     * @var File $photoFile Image file
     *
     * @Vich\UploadableField(mapping="photoName", fileNameProperty="photoName")
     */
    private $photoFile;

    /**
     * @var string $photoName Photo of name
     *
     * @ORM\Column(type="string", length=255)
     */
    private $photoName;

    /**
     * @var string $name Name
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
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
     * @Assert\Type(type="string")
     *
     * @JMS\Expose
     * @JMS\Since("1.0")
     *
     * @Gedmo\Versioned
     */
    private $description;

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
     * @param Sight $sight sight
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
     * Set photo file
     *
     * @param File|UploadedFile $image Image
     *
     * @return $this
     */
    public function setPhotoFile(File $photo = null)
    {
        $this->photoFile = $photo;

        if ($photo) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get photo file
     *
     * @return File
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * Set photo name
     *
     * @param string $photoName Photo name
     *
     * @return $this
     */
    public function setPhotoName($photoName)
    {
        $this->photoName = $photoName;

        return $this;
    }

    /**
     * Get photo name
     *
     * @return string
     */
    public function getPhotoName()
    {
        return $this->photoName;
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
     * @param string $name name
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
}