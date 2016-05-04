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
            ->setEnabled(true);
        $this->setReference('user-1', $user1);
        $manager->persist($user1);

        $user2 = (new User())
            ->setEmail('admin@gmail.com')
            ->setUsername('admin')
            ->addRole('ROLE_ADMIN')
            ->setPlainPassword('1234')
            ->setEnabled(true);
        $this->setReference('user-2', $user2);
        $manager->persist($user2);

        $manager->flush();
    }
}
