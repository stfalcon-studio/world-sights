<?php

namespace AppBundle\Event;

use AppBundle\Entity\Friend;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Add User Event
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserEvent extends Event
{
    /**
     * @var TokenStorageInterface $tokenStorage Token storage
     */
    private $tokenStorage;

    /**
     * @var Friend $friend Friend
     */
    private $friend;

    /**
     * Constructor
     *
     * @param TokenStorageInterface $tokenStorage Token storage
     * @param Friend                  $friend         Friend
     */
    public function __construct(TokenStorageInterface $tokenStorage, Friend $friend)
    {
        $this->tokenStorage = $tokenStorage;
        $this->friend         = $friend;
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
     * Get friend
     *
     * @return Friend
     */
    public function getFriend()
    {
        return $this->friend;
    }
}
