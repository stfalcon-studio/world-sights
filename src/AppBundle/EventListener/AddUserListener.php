<?php

namespace AppBundle\EventListener;

use AppBundle\Event\AddUserEvent;
use Doctrine\ORM\EntityManager;

/**
 * Add User Listener
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserListener
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
     * On user add
     *
     * @param AddUserEvent $args
     */
    public function onUserAdd(AddUserEvent $args)
    {
        $user   = $args->getTokenStorage()->getToken()->getUser();
        $friend = $args->getFriend();

        $friend->setUser($user);
    }
}
