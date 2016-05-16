<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use AppBundle\Entity\Sight;
use AppBundle\Entity\SightPhoto;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadSightPhotoData
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LoadSightPhotoData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @var string $imageFixturesDirectory Image fixtures directory
     */
    private $imageFixturesDirectory = '';

    /**
     * @var string $imageWebDirectory Image Web directory
     */
    private $imageWebDirectory = __DIR__.'/../../../../web/images/sights';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->imageFixturesDirectory = __DIR__.'/../../Resources/fixtures/images/sights';
    }

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
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user1 */
        /** @var User $user2 */
        /** @var User $user3 */
        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');
        $user3 = $this->getReference('user-3');

        /** @var Sight $sightKamyanetsCastle */
        /** @var Sight $sightMinskLibrary */
        /** @var Sight $sightWarszawaCastle */
        $sightKamyanetsCastle = $this->getReference('sight-Kamianets-Podilskyi-castle');
        $sightMinskLibrary    = $this->getReference('sight-Minsk-library');
        $sightWarszawaCastle  = $this->getReference('sight-Warszawa-castle');

        $this->prepareDirectories();
        $this->prepareImages();

        $sightPhoto = (new SightPhoto())
            ->setUser($user1)
            ->setSight($sightKamyanetsCastle)
            ->setPhotoName('kamyanets1.jpg');
        $manager->persist($sightPhoto);

        $sightPhoto = (new SightPhoto())
            ->setUser($user1)
            ->setSight($sightWarszawaCastle)
            ->setPhotoName('warszawa1.jpg');
        $manager->persist($sightPhoto);

        $sightPhoto = (new SightPhoto())
            ->setUser($user1)
            ->setSight($sightMinskLibrary)
            ->setPhotoName('minsk1.jpg');
        $manager->persist($sightPhoto);

        $sightPhoto = (new SightPhoto())
            ->setUser($user2)
            ->setSight($sightMinskLibrary)
            ->setPhotoName('minsk2.jpg');
        $manager->persist($sightPhoto);

        $sightPhoto = (new SightPhoto())
            ->setUser($user2)
            ->setSight($sightWarszawaCastle)
            ->setPhotoName('warszawa2.jpg');
        $manager->persist($sightPhoto);

        $sightPhoto = (new SightPhoto())
            ->setUser($user3)
            ->setSight($sightKamyanetsCastle)
            ->setPhotoName('kamyanets3.jpg');
        $manager->persist($sightPhoto);

        $manager->flush();
    }

    /**
     * Prepare directories
     */
    private function prepareDirectories()
    {
        if (!file_exists($this->imageWebDirectory)) {
            mkdir($this->imageWebDirectory, 0777, true);
        }
    }

    /**
     * Prepare images
     */
    private function prepareImages()
    {
        copy($this->imageFixturesDirectory.'/kamyanets1.jpg', $this->imageWebDirectory.'/kamyanets1.jpg');
        copy($this->imageFixturesDirectory.'/kamyanets2.jpg', $this->imageWebDirectory.'/kamyanets2.jpg');
        copy($this->imageFixturesDirectory.'/kamyanets3.jpg', $this->imageWebDirectory.'/kamyanets3.jpg');
        copy($this->imageFixturesDirectory.'/minsk1.jpg', $this->imageWebDirectory.'/minsk1.jpg');
        copy($this->imageFixturesDirectory.'/minsk2.jpg', $this->imageWebDirectory.'/minsk2.jpg');
        copy($this->imageFixturesDirectory.'/warszawa1.jpg', $this->imageWebDirectory.'/warszawa1.jpg');
        copy($this->imageFixturesDirectory.'/warszawa2.jpg', $this->imageWebDirectory.'/warszawa2.jpg');
    }
}
