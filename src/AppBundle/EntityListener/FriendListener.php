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

    /**
     * Post persist
     *
     * @param Friend             $friend Friend
     * @param LifecycleEventArgs $args   Arguments
     */
    public function postPersist(Friend $friend, LifecycleEventArgs $args)
    {
        if ($friend instanceof Friend) {
            $token = $this->tokenStorage->getToken();

            if (null !== $token) {
                /** @var User $user */
                $user = $token->getUser();
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

    /**
     * Post update
     *
     * @param Friend             $friend Friend
     * @param LifecycleEventArgs $args   Arguments
     */
    public function postUpdate(Friend $friend, LifecycleEventArgs $args)
    {
        if ($friend instanceof Friend) {
            if ($token = $this->tokenStorage->getToken()) {
                /** @var User $user */
                $user = $token->getUser();
                if ($user === $friend->getUser()) {
                    $em = $args->getEntityManager();

                    $associatedFriend = $em->getRepository('AppBundle:Friend')
                                           ->findFriendByUserFriend($friend->getFriend(), $user);

                    $status = $friend->getStatus();
                    switch ($status) {
                        case FriendStatusType::REJECTED:
                        case FriendStatusType::RECEIVED:
                            $associatedFriend->setStatus(FriendStatusType::SENT);
                            break;
                        case FriendStatusType::ACCEPTED:
                            $associatedFriend->setStatus(FriendStatusType::ACCEPTED);
                            break;
                    }

                    $em->persist($associatedFriend);
                    $em->flush();
                }
            }
        }
    }

    /**
     * Post remove
     *
     * @param Friend             $friend Friend
     * @param LifecycleEventArgs $args   Arguments
     */
    public function postRemove(Friend $friend, LifecycleEventArgs $args)
    {
        if ($friend instanceof Friend) {
            if ($token = $this->tokenStorage->getToken()) {
                /** @var User $user */
                $user = $token->getUser();
                if ($user === $friend->getUser()) {
                    $em = $args->getEntityManager();

                    $associatedFriend = $em->getRepository('AppBundle:Friend')
                                           ->findFriendByUserFriend($friend->getFriend(), $user);

                    $em->remove($associatedFriend);
                    $em->flush();
                }
            }
        }
    }
}
