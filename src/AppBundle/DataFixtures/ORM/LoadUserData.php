<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadUserData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadUserData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user1 = (new User())
            ->setEmail('user@gmail.com')
            ->setUsername('user')
            ->setPlainPassword('1234')
            ->setAccessToken('1e5008f3677f7ba2a8bd8e47b8c0c6')
            ->setRefreshToken('c25e0d5e51271b4a03364961dc9335')
            ->setExpiredAt((new \DateTime())->modify('12 hour'))
            ->setEnabled(true);
        $this->setReference('user-1', $user1);
        $manager->persist($user1);

        $user2 = (new User())
            ->setEmail('admin@gmail.com')
            ->setUsername('admin')
            ->addRole('ROLE_ADMIN')
            ->setPlainPassword('1234')
            ->setAccessToken('434062166b975868689a841f96912a')
            ->setRefreshToken('4b49d69b365b290ececc9f00edaa29')
            ->setExpiredAt((new \DateTime())->modify('12 hour'))
            ->setEnabled(true);
        $this->setReference('user-2', $user2);
        $manager->persist($user2);

        $manager->flush();
    }
}
