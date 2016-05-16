<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTour;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightTourData class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightTourData extends AbstractFixture implements DependentFixtureInterface
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
        /** @var Sight $sightKamyanetsCastle */
        /** @var Sight $sightMinskLibrary */
        /** @var Sight $sightWarzavaCastle */
        $sightKamyanetsCastle = $this->getReference('sight-Kamianets-Podilskyi-castle');
        $sightMinskLibrary    = $this->getReference('sight-Minsk-library');
        $sightWarzavaCastle   = $this->getReference('sight-Warszawa-castle');

        $sightTour = (new SightTour())
            ->setName('Екскурсійна програма по місту Кам’янець-Подільському')
            ->setCompanyName('7 днів')
            ->setTourLink('http://www.7dniv.ua/ua/tourism-directions')
            ->setPrice(500)
            ->setSight($sightKamyanetsCastle);
        $manager->persist($sightTour);

        $sightTour = (new SightTour())
            ->setName('Вікенд у Мінську')
            ->setCompanyName('Аккорд-тур')
            ->setTourLink('http://www.akkord-tour.com.ua/product.php/product_id/8152/category_id/109/land_id/42/lang/ua')
            ->setPrice(1900)
            ->setSight($sightMinskLibrary);
        $manager->persist($sightTour);

        $sightTour = (new SightTour())
            ->setName('Подорож в минуле')
            ->setCompanyName('Тамтур')
            ->setTourLink('http://tamtour.com.ua/7')
            ->setPrice(2100)
            ->setSight($sightMinskLibrary);
        $manager->persist($sightTour);

        $sightTour = (new SightTour())
            ->setName('Королівський шлях')
            ->setCompanyName('Rich tour')
            ->setTourLink('http://rich-tour.com/publ/ekskursijni_turi/korolivskij_shljakh/5-1-0-134')
            ->setPrice(4500)
            ->setSight($sightWarzavaCastle);
        $manager->persist($sightTour);

        $manager->flush();
    }
}
