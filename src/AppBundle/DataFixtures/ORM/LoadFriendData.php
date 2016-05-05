<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Entity\Friend;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadFriendData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadFriendData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user1 */
        /** @var User $user2 */
        /** @var User $user3 */
        /** @var User $user4 */
        /** @var User $user5 */
        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');
        $user3 = $this->getReference('user-3');
        $user4 = $this->getReference('user-4');
        $user5 = $this->getReference('user-5');

        $friend1 = (new Friend())
            ->setUser($user1)
            ->setFriend($user2)
            ->setStatus(FriendStatusType::ACCEPTED);
        $manager->persist($friend1);

        $friend2 = (new Friend())
            ->setUser($user1)
            ->setFriend($user3)
            ->setStatus(FriendStatusType::ACCEPTED);
        $manager->persist($friend2);

        $friend3 = (new Friend())
            ->setUser($user1)
            ->setFriend($user4)
            ->setStatus(FriendStatusType::REJECTED);
        $manager->persist($friend3);

        $friend4 = (new Friend())
            ->setUser($user1)
            ->setFriend($user5)
            ->setStatus(FriendStatusType::SENT);
        $manager->persist($friend4);

        $friend5 = (new Friend())
            ->setUser($user2)
            ->setFriend($user1)
            ->setStatus(FriendStatusType::ACCEPTED);
        $manager->persist($friend5);

        $friend6 = (new Friend())
            ->setUser($user3)
            ->setFriend($user1)
            ->setStatus(FriendStatusType::ACCEPTED);
        $manager->persist($friend6);

        $manager->flush();
    }
}
