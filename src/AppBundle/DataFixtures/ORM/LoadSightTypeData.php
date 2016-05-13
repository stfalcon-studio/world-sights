<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\SightType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightTypeData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightTypeData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $sightType = (new SightType())
            ->setName('Замок');
        $this->setReference('sight-type-castle', $sightType);
        $manager->persist($sightType);

        $sightType = (new SightType())
            ->setName('Заповідник');
        $this->setReference('sight-type-reserve', $sightType);
        $manager->persist($sightType);

        $sightType = (new SightType())
            ->setName('Бібліотека');
        $this->setReference('sight-type-library', $sightType);
        $manager->persist($sightType);

        $sightType = (new SightType())
            ->setName('Острів');
        $this->setReference('sight-type-island', $sightType);
        $manager->persist($sightType);

        $sightType = (new SightType())
            ->setName('Термальні вани');
        $this->setReference('sight-type-thermal-bath', $sightType);
        $manager->persist($sightType);

        $manager->flush();
    }
}
