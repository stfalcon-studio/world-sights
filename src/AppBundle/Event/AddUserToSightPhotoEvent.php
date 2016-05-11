<?php

namespace AppBundle\Event;

use AppBundle\Entity\SightPhoto;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Add User to Sight Photo Event
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserToSightPhotoEvent extends Event
{
    /**
     * @var TokenStorageInterface $tokenStorage Token storage
     */
    private $tokenStorage;

    /**
     * @var SightPhoto $sightPhoto SightPhoto
     */
    private $sightPhoto;

    /**
     * Constructor
     *
     * @param TokenStorageInterface $tokenStorage Token storage
     * @param SightPhoto            $sightPhoto   SightPhoto
     */
    public function __construct(TokenStorageInterface $tokenStorage, SightPhoto $sightPhoto)
    {
        $this->tokenStorage = $tokenStorage;
        $this->sightPhoto   = $sightPhoto;
    }

    /**
     * Get token storage
     *
     * @return TokenStorageInterface
     */
    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }

    /**
     * Get sight photo
     *
     * @return SightPhoto
     */
    public function getSightPhoto()
    {
        return $this->sightPhoto;
    }
}
