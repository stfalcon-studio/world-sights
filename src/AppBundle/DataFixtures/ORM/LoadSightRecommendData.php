<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightRecommend;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightRecommendData class
 *
 * @author Yevgeniy Zholkevskiy <zhenya.zholkevskiy@gmail.com>
 */
class LoadSightRecommendData extends AbstractFixture implements DependentFixtureInterface
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
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user1 */
        /** @var User $user2 */
        /** @var User $user3 */
        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');
        $user3 = $this->getReference('user-3');

        /** @var Sight $sightKamyanetsCastle */
        /** @var Sight $sightMinskLibrary */
        /** @var Sight $sightWarzavaCastle */
        $sightKamyanetsCastle = $this->getReference('sight-Kamianets-Podilskyi-castle');
        $sightMinskLibrary    = $this->getReference('sight-Minsk-library');
        $sightWarzavaCastle   = $this->getReference('sight-Warszawa-castle');

        $sightRecommend = (new SightRecommend())
            ->setUser($user1)
            ->setSight($sightKamyanetsCastle)
            ->setMessage('Всім рекомендую це місце, краса заворожує');
        $manager->persist($sightRecommend);

        $sightRecommend = (new SightRecommend())
            ->setUser($user1)
            ->setSight($sightMinskLibrary)
            ->setMessage('Розміри бібліотеки зашкалюють');
        $manager->persist($sightRecommend);

        $sightRecommend = (new SightRecommend())
            ->setUser($user1)
            ->setSight($sightWarzavaCastle)
            ->setMessage('Замок прямо у центрі міста, по-моєуму не погано? Усім рекомендую!');
        $manager->persist($sightRecommend);

        $sightRecommend = (new SightRecommend())
            ->setUser($user2)
            ->setSight($sightKamyanetsCastle)
            ->setMessage('Середньовічний замок, найкраще, що може бути:)');
        $manager->persist($sightRecommend);

        $sightRecommend = (new SightRecommend())
            ->setUser($user2)
            ->setSight($sightWarzavaCastle)
            ->setMessage('З цим замком Варшава, здається нейвірною');
        $manager->persist($sightRecommend);

        $sightRecommend = (new SightRecommend())
            ->setUser($user3)
            ->setSight($sightMinskLibrary)
            ->setMessage('Скільки ж там кнжиок, приїдьте і гляньте!');
        $manager->persist($sightRecommend);

        $sightRecommend = (new SightRecommend())
            ->setUser($user3)
            ->setSight($sightKamyanetsCastle)
            ->setMessage('Краса середновічних мурів у Кам’янці, респект за такий відпочинок');
        $manager->persist($sightRecommend);

        $manager->flush();
    }
}
