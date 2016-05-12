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

        $locality = (new Locality())
            ->setName('Кам\'янець-Подільський')
            ->setCountry($countryUkraine)
            ->setSlug('kamyanets');
        $this->setReference('locality-Kamyanets', $locality);
        $manager->persist($locality);

        $locality = (new Locality())
            ->setName('Хотин')
            ->setCountry($countryUkraine)
            ->setSlug('hotin');
        $this->setReference('locality-Hotin', $locality);
        $manager->persist($locality);

        $locality = (new Locality())
            ->setName('Запоріжжя')
            ->setCountry($countryUkraine)
            ->setSlug('zaporizha');
        $this->setReference('locality-Zaporizhia', $locality);
        $manager->persist($locality);

        $locality = (new Locality())
            ->setName('Мінськ')
            ->setCountry($countryBelarus)
            ->setSlug('minsk');
        $this->setReference('locality-Minsk', $locality);
        $manager->persist($locality);

        $locality = (new Locality())
            ->setName('Варшава')
            ->setCountry($countryPoland)
            ->setSlug('warzava');
        $this->setReference('locality-Warszawa', $locality);
        $manager->persist($locality);

        $locality = (new Locality())
            ->setName('Будапешт')
            ->setCountry($countryHungary)
            ->setSlug('budapest');
        $this->setReference('locality-Budapest', $locality);
        $manager->persist($locality);

        $locality = (new Locality())
            ->setName('Київ')
            ->setCountry($countryUkraine)
            ->setSlug('kiev');
        $this->setReference('locality-Kiev', $locality);
        $manager->persist($locality);

        $manager->flush();
    }
}
