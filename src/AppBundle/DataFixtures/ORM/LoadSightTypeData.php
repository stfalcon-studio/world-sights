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
        $sightType1 = (new SightType())
            ->setName('Замок');
        $this->setReference('sight-type-castle', $sightType1);
        $manager->persist($sightType1);

        $sightType2 = (new SightType())
            ->setName('Заповідник');
        $this->setReference('sight-type-reserve', $sightType2);
        $manager->persist($sightType2);

        $sightType3 = (new SightType())
            ->setName('Бібліотека');
        $this->setReference('sight-type-library', $sightType3);
        $manager->persist($sightType3);

        $sightType4 = (new SightType())
            ->setName('Острів');
        $this->setReference('sight-type-island', $sightType4);
        $manager->persist($sightType4);

        $sightType5 = (new SightType())
            ->setName('Термальні вани');
        $this->setReference('sight-type-thermal-bath', $sightType5);
        $manager->persist($sightType5);

        $manager->flush();
    }
}
