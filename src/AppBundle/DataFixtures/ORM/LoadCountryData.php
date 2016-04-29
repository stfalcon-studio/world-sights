<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Country;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadCountryData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadCountryData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $country1 = (new Country())
            ->setName('Україна')
            ->setSlug('ukraine');
        $this->setReference('country-Ukraine', $country1);
        $manager->persist($country1);

        $country2 = (new Country())
            ->setName('Білорусь')
            ->setSlug('belarus');
        $this->setReference('country-Belarus', $country2);
        $manager->persist($country2);

        $country3 = (new Country())
            ->setName('Польща')
            ->setSlug('poland');
        $this->setReference('country-Poland', $country3);
        $manager->persist($country3);

        $country4 = (new Country())
            ->setName('Угорщина')
            ->setSlug('hungary');
        $this->setReference('country-Hungary', $country4);
        $manager->persist($country4);

        $manager->flush();
    }
}
