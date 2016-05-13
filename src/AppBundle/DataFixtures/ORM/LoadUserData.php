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
        $user = (new User())
            ->setEmail('user@gmail.com')
            ->setUsername('user')
            ->setPlainPassword('1234')
            ->setAccessToken('1e5008f3677f7ba2a8bd8e47b8c0c6')
            ->setRefreshToken('c25e0d5e51271b4a03364961dc9335')
            ->setExpiredAt((new \DateTime())->modify('12 hour'))
            ->setEnabled(true);
        $this->setReference('user-1', $user);
        $manager->persist($user);

        $user = (new User())
            ->setEmail('admin@gmail.com')
            ->setUsername('admin')
            ->addRole('ROLE_ADMIN')
            ->setPlainPassword('1234')
            ->setAccessToken('434062166b975868689a841f96912a')
            ->setRefreshToken('4b49d69b365b290ececc9f00edaa29')
            ->setExpiredAt((new \DateTime())->modify('12 hour'))
            ->setEnabled(true);
        $this->setReference('user-2', $user);
        $manager->persist($user);

        $user = (new User())
            ->setEmail('user3@gmail.com')
            ->setUsername('user3')
            ->setPlainPassword('1234')
            ->setEnabled(true);
        $this->setReference('user-3', $user);
        $manager->persist($user);

        $user = (new User())
            ->setEmail('user4@gmail.com')
            ->setUsername('user4')
            ->setPlainPassword('1234')
            ->setEnabled(true);
        $this->setReference('user-4', $user);
        $manager->persist($user);

        $user = (new User())
            ->setEmail('user5@gmail.com')
            ->setUsername('user5')
            ->setPlainPassword('1234')
            ->setEnabled(true);
        $this->setReference('user-5', $user);
        $manager->persist($user);

        $user = (new User())
            ->setEmail('user6@gmail.com')
            ->setUsername('user6')
            ->setPlainPassword('1234')
            ->setEnabled(true);
        $this->setReference('user-6', $user);
        $manager->persist($user);

        $user = (new User())
            ->setEmail('user7@gmail.com')
            ->setUsername('user7')
            ->setPlainPassword('1234')
            ->setEnabled(true);
        $this->setReference('user-7', $user);
        $manager->persist($user);

        $manager->flush();
    }
}
