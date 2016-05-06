<?php

namespace AppBundle\EntityListener;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Entity\Friend;
use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Friend Listener
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class FriendListener
{
    /**
     * @var TokenStorageInterface $tokenStorage Token storage
     */
    private $tokenStorage;

    /**
     * Constructor
     *
     * @param TokenStorageInterface $tokenStorage Token storage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function postPersist(Friend $friend, LifecycleEventArgs $args)
    {
        if ($friend instanceof Friend) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();
            if ($user === $friend->getUser()) {
                $em = $args->getEntityManager();

                $associatedFriend = (new Friend())
                    ->setUser($friend->getFriend())
                    ->setFriend($friend->getUser())
                    ->setStatus(FriendStatusType::RECEIVED);

                $em->persist($associatedFriend);
                $em->flush();
            }
        }
    }
}
