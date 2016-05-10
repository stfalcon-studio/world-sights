<?php

namespace AppBundle\Event;

use AppBundle\Entity\SightVisit;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Add User to Sight Visit Event
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserToSightVisitEvent extends Event
{
    /**
     * @var TokenStorageInterface $tokenStorage Token storage
     */
    private $tokenStorage;

    /**
     * @var SightVisit $sightVisit Sight visit
     */
    private $sightVisit;

    /**
     * Constructor
     *
     * @param TokenStorageInterface $tokenStorage Token storage
     * @param SightVisit            $sightVisit   Sight visit
     */
    public function __construct(TokenStorageInterface $tokenStorage, SightVisit $sightVisit)
    {
        $this->tokenStorage = $tokenStorage;
        $this->sightVisit   = $sightVisit;
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
     * Get sight visit
     *
     * @return SightVisit
     */
    public function getSightVisit()
    {
        return $this->sightVisit;
    }
}
