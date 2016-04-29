<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use AppBundle\Entity\Locality;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadLocalityData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadLocalityData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadCountryData',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Country $countryUkraine */
        /** @var Country $countryBelarus */
        /** @var Country $countryPoland */
        /** @var Country $countryHungary */
        $countryUkraine = $this->getReference('country-Ukraine');
        $countryBelarus = $this->getReference('country-Belarus');
        $countryPoland  = $this->getReference('country-Poland');
        $countryHungary = $this->getReference('country-Hungary');

        $locality1 = (new Locality())
            ->setName('Кам\'янець-Подільський')
            ->setCountry($countryUkraine)
            ->setSlug('kamyanets');
        $this->setReference('locality-Kamyanets', $locality1);
        $manager->persist($locality1);

        $locality2 = (new Locality())
            ->setName('Хотин')
            ->setCountry($countryUkraine)
            ->setSlug('hotin');
        $this->setReference('locality-Hotin', $locality2);
        $manager->persist($locality2);

        $locality3 = (new Locality())
            ->setName('Запоріжжя')
            ->setCountry($countryUkraine)
            ->setSlug('zaporizha');
        $this->setReference('locality-Zaporizhia', $locality3);
        $manager->persist($locality3);

        $locality4 = (new Locality())
            ->setName('Мінськ')
            ->setCountry($countryBelarus)
            ->setSlug('minsk');
        $this->setReference('locality-Minsk', $locality4);
        $manager->persist($locality4);

        $locality5 = (new Locality())
            ->setName('Варшава')
            ->setCountry($countryPoland)
            ->setSlug('warzava');
        $this->setReference('locality-Warszawa', $locality5);
        $manager->persist($locality5);

        $locality6 = (new Locality())
            ->setName('Будапешт')
            ->setCountry($countryHungary)
            ->setSlug('budapest');
        $this->setReference('locality-Budapest', $locality6);
        $manager->persist($locality6);

        $locality7 = (new Locality())
            ->setName('Київ')
            ->setCountry($countryUkraine)
            ->setSlug('kiev');
        $this->setReference('locality-Kiev', $locality7);
        $manager->persist($locality7);

        $manager->flush();
    }
}
