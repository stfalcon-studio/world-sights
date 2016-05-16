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
        $country = (new Country())
            ->setName('Україна')
            ->setSlug('ukraine');
        $this->setReference('country-Ukraine', $country);
        $manager->persist($country);

        $country = (new Country())
            ->setName('Білорусь')
            ->setSlug('belarus');
        $this->setReference('country-Belarus', $country);
        $manager->persist($country);

        $country = (new Country())
            ->setName('Польща')
            ->setSlug('poland');
        $this->setReference('country-Poland', $country);
        $manager->persist($country);

        $country = (new Country())
            ->setName('Угорщина')
            ->setSlug('hungary');
        $this->setReference('country-Hungary', $country);
        $manager->persist($country);

        $manager->flush();
    }
}
