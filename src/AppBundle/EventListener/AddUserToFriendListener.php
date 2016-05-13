<?php

namespace AppBundle\EventListener;

use AppBundle\Event\AddUserToFriendEvent;
use Doctrine\ORM\EntityManager;

/**
 * Add User Listener
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserToFriendListener
{
    /**
     * @var EntityManager $em Entity manager
     */
    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $em Entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * On user add to friend
     *
     * @param AddUserToFriendEvent $args Add user to friend event
     */
    public function onUserAddToFriend(AddUserToFriendEvent $args)
    {
        $user   = $args->getTokenStorage()->getToken()->getUser();
        $friend = $args->getFriend();

        $friend->setUser($user);
    }
}
