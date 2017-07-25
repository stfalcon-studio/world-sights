<?php

namespace AppBundle\EventListener;

use AppBundle\Event\AddUserToSightPhotoEvent;
use Doctrine\ORM\EntityManager;

/**
 * Add User to Sight Photo Listener
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AddUserToSightPhotoListener
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
     * On user add to sight photo
     *
     * @param AddUserToSightPhotoEvent $args Arguments
     */
    public function onUserAddToSightPhoto(AddUserToSightPhotoEvent $args)
    {
        $user       = $args->getTokenStorage()->getToken()->getUser();
        $sightPhoto = $args->getSightPhoto();

        $sightPhoto->setUser($user);
    }
}
