<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightVisit;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightVisitData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightVisitData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadCountryData',
            'AppBundle\DataFixtures\ORM\LoadLocalityData',
            'AppBundle\DataFixtures\ORM\LoadSightTypeData',
            'AppBundle\DataFixtures\ORM\LoadSightData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user1 */
        /** @var User $user2 */
        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');

        /** @var Sight $sightKamyanetsCastle */
        /** @var Sight $sightMinskLibrary */
        /** @var Sight $sightWarzavaCastle */
        $sightKamyanetsCastle = $this->getReference('sight-Kamianets-Podilskyi-castle');
        $sightMinskLibrary    = $this->getReference('sight-Minsk-library');
        $sightWarzavaCastle   = $this->getReference('sight-Warszawa-castle');

        $sightVisit1 = (new SightVisit())
            ->setUser($user1)
            ->setSight($sightKamyanetsCastle)
            ->setDate((new \DateTime())->modify('-5 day'));
        $manager->persist($sightVisit1);

        $sightVisit2 = (new SightVisit())
            ->setUser($user1)
            ->setSight($sightMinskLibrary)
            ->setDate((new \DateTime())->modify('-7 day'));
        $manager->persist($sightVisit2);

        $sightVisit3 = (new SightVisit())
            ->setUser($user1)
            ->setSight($sightWarzavaCastle)
            ->setDate((new \DateTime())->modify('-2 month'));
        $manager->persist($sightVisit3);

        $sightVisit4 = (new SightVisit())
            ->setUser($user2)
            ->setSight($sightWarzavaCastle)
            ->setDate((new \DateTime())->modify('-1 month'));
        $manager->persist($sightVisit4);

        $sightVisit5 = (new SightVisit())
            ->setUser($user2)
            ->setSight($sightKamyanetsCastle)
            ->setDate((new \DateTime())->modify('-5 day'));
        $manager->persist($sightVisit5);

        $manager->flush();
    }
}
