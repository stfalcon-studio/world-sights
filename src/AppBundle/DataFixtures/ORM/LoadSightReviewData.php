<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightReview;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightReviewData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightReviewData extends AbstractFixture implements DependentFixtureInterface
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
        /** @var User $user3 */
        /** @var User $user4 */
        /** @var User $user5 */
        /** @var User $user6 */
        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');
        $user3 = $this->getReference('user-3');

        /** @var Sight $sightKamyanetsCastle */
        /** @var Sight $sightMinskLibrary */
        /** @var Sight $sightWarzavaCastle */
        $sightKamyanetsCastle = $this->getReference('sight-Kamianets-Podilskyi-castle');
        $sightMinskLibrary    = $this->getReference('sight-Minsk-library');
        $sightWarzavaCastle   = $this->getReference('sight-Warszawa-castle');

        $sightReview = (new SightReview())
            ->setUser($user1)
            ->setSight($sightKamyanetsCastle)
            ->setTopic('Чудовий Кам\'янецький замок')
            ->setDescription('Один із найбільших замків в Україні')
            ->setMark(5);
        $manager->persist($sightReview);

        $sightReview = (new SightReview())
            ->setUser($user1)
            ->setSight($sightMinskLibrary)
            ->setTopic('Неймовірна бібліотека у столиці Білорусі')
            ->setDescription('Одна із найбільших бібліотека у світі, вражає своїми розмірами')
            ->setMark(5);
        $manager->persist($sightReview);

        $sightReview = (new SightReview())
            ->setUser($user1)
            ->setSight($sightWarzavaCastle)
            ->setTopic('Замок у самому центрі Варшами')
            ->setDescription('Середньовічний замок у цетрі Варшави')
            ->setMark(4);
        $manager->persist($sightReview);

        $sightReview = (new SightReview())
            ->setUser($user2)
            ->setSight($sightKamyanetsCastle)
            ->setTopic('Найбільший замок в Україні')
            ->setDescription('Чудовий відпочинок')
            ->setMark(5);
        $manager->persist($sightReview);

        $sightReview = (new SightReview())
            ->setUser($user2)
            ->setSight($sightMinskLibrary)
            ->setTopic('Здоровезна бібліотека у Мінську')
            ->setDescription('Бібліотека:)')
            ->setMark(5);
        $manager->persist($sightReview);

        $sightReview = (new SightReview())
            ->setUser($user2)
            ->setSight($sightWarzavaCastle)
            ->setTopic('Звичайний замок у звичайній Варшами')
            ->setDescription('Варшавський замок')
            ->setMark(3);
        $manager->persist($sightReview);

        $sightReview = (new SightReview())
            ->setUser($user3)
            ->setSight($sightKamyanetsCastle)
            ->setTopic('Одне із семи чудес України')
            ->setDescription('Позитвні відчуття від подорожі середньовічними мурми, цього замку')
            ->setMark(5);
        $manager->persist($sightReview);

        $manager->flush();
    }
}
