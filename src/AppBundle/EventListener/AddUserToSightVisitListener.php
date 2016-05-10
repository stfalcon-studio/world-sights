<?php

namespace AppBundle\EventListener;

use AppBundle\Event\AddUserToSightVisitEvent;
use Doctrine\ORM\EntityManager;

/**
 * Add User to Sight Visit Listener
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserToSightVisitListener
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
     * On user add to sight visit
     *
     * @param AddUserToSightVisitEvent $args
     */
    public function onUserAddToSightVisit(AddUserToSightVisitEvent $args)
    {
        $user       = $args->getTokenStorage()->getToken()->getUser();
        $sightVisit = $args->getSightVisit();

        $sightVisit->setUser($user);
    }
}
