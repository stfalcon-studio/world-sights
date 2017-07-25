<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Locality;
use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTicket;
use AppBundle\DBAL\Types\SightTicketType;
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
        $sightMinskLibrary    = $this->getReference('sight-Minsk-library');
        $sightWarzavaCastle   = $this->getReference('sight-Warszawa-castle');

        $sightTicket = (new SightTicket())
            ->setType(SightTicketType::TRAIN_TICKET)
            ->setLinkBuy('https://gd.tickets.ua/uk/railwaytracker/table/Kamenetz-Podolsk~2200260')
            ->setSight($sightKamyanetsCastle)
            ->setFrom($localityKiev)
            ->setTo($localityKamyanets);
        $manager->persist($sightTicket);

        $sightTicket = (new SightTicket())
            ->setType(SightTicketType::TRAIN_TICKET)
            ->setLinkBuy('http://poizd.turcompas.com/raspisanie/22260')
            ->setSight($sightKamyanetsCastle)
            ->setFrom($localityZaporizhia)
            ->setTo($localityKamyanets);
        $manager->persist($sightTicket);

        $sightTicket = (new SightTicket())
            ->setType(SightTicketType::PLANE_TICKET)
            ->setLinkBuy('http://www.flyuia.com/avia-ua/from-kiev-Warsaw.html')
            ->setSight($sightWarzavaCastle)
            ->setFrom($localityKiev)
            ->setTo($localityWarszawa);
        $manager->persist($sightTicket);

        $sightTicket = (new SightTicket())
            ->setType(SightTicketType::BUS_TICKET)
            ->setLinkBuy('http://ecolines.by/ru/predlozhenija/307-minsk-warszawa')
            ->setSight($sightWarzavaCastle)
            ->setFrom($localityMinsk)
            ->setTo($localityWarszawa);
        $manager->persist($sightTicket);

        $sightTicket = (new SightTicket())
            ->setType(SightTicketType::PLANE_TICKET)
            ->setLinkBuy('http://www.flyuia.com/avia-ua/from-Kiev-to-Minsk.html')
            ->setSight($sightMinskLibrary)
            ->setFrom($localityKiev)
            ->setTo($localityMinsk);
        $manager->persist($sightTicket);

        $manager->flush();
    }
}
