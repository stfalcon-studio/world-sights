<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Locality;
use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTicket;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightTicketData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightTicketData extends AbstractFixture implements DependentFixtureInterface
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Locality $localityKamyanets */
        /** @var Locality $localityZaporizhia */
        /** @var Locality $localityMinsk */
        /** @var Locality $localityWarszawa */
        /** @var Locality $localityKiev */
        $localityKamyanets  = $this->getReference('locality-Kamyanets');
        $localityZaporizhia = $this->getReference('locality-Zaporizhia');
        $localityMinsk      = $this->getReference('locality-Minsk');
        $localityWarszawa   = $this->getReference('locality-Warszawa');
        $localityKiev       = $this->getReference('locality-Kiev');

        /** @var Sight $sightKamyanetsCastle */
        /** @var Sight $sightMinskLibrary */
        /** @var Sight $sightWarzavaCastle */
        $sightKamyanetsCastle = $this->getReference('sight-Kamianets-Podilskyi-castle');
        $sightMinskLibrary = $this->getReference('sight-Minsk-library');
        $sightWarzavaCastle = $this->getReference('sight-Warszawa-castle');

        $sightTicket1 = (new SightTicket())
            ->setType('залізничний квиток')
            ->setLinkBuy('https://gd.tickets.ua/uk/railwaytracker/table/Kamenetz-Podolsk~2200260')
            ->setSlug('Київ-Кам’янець залізничний квиток')
            ->setSight($sightKamyanetsCastle)
            ->setFrom($localityKiev)
            ->setTo($localityKamyanets);
        $manager->persist($sightTicket1);

        $sightTicket2 = (new SightTicket())
            ->setType('залізничний квиток')
            ->setLinkBuy('http://poizd.turcompas.com/raspisanie/22260')
            ->setSlug('Запоріжжя-Кам’янець залізничний квиток')
            ->setSight($sightKamyanetsCastle)
            ->setFrom($localityZaporizhia)
            ->setTo($localityKamyanets);
        $manager->persist($sightTicket2);

        $sightTicket3 = (new SightTicket())
            ->setType('авіабілет')
            ->setLinkBuy('http://www.flyuia.com/avia-ua/from-kiev-Warsaw.html')
            ->setSlug('Київ-Варшава авіабілет')
            ->setSight($sightWarzavaCastle)
            ->setFrom($localityKiev)
            ->setTo($localityWarszawa);
        $manager->persist($sightTicket3);

        $sightTicket4 = (new SightTicket())
            ->setType('автобус')
            ->setLinkBuy('http://ecolines.by/ru/predlozhenija/307-minsk-warszawa')
            ->setSlug('Мінськ-Варшава автобус')
            ->setSight($sightWarzavaCastle)
            ->setFrom($localityMinsk)
            ->setTo($localityWarszawa);
        $manager->persist($sightTicket4);

        $sightTicket5 = (new SightTicket())
            ->setType('авіабілет')
            ->setLinkBuy('http://www.flyuia.com/avia-ua/from-Kiev-to-Minsk.html')
            ->setSlug('Київ-Мінськ авіабілет')
            ->setSight($sightMinskLibrary)
            ->setFrom($localityKiev)
            ->setTo($localityMinsk);
        $manager->persist($sightTicket5);

        $manager->flush();
    }
}
