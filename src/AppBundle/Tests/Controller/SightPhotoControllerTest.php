<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DBAL\Types\SightTicketType;
use AppBundle\Entity\Locality;
use AppBundle\Entity\SightType;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightPhotoControllerTest extends WebTestCase
{
    /** @var Client $client */
    private $client;

    /** @var ObjectManager */
    private $manager;

    public function setUp()
    {
        $this->getFixtures();

        parent::setUp();

        $this->client = static::makeClient();
        $this->client->setServerParameter('HTTP_X_AUTH_TOKEN', '1e5008f3677f7ba2a8bd8e47b8c0c6');

        $this->manager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    /**
     * Load fixtures for tests
     */
    private function getFixtures()
    {
        $fixtures = [
            'AppBundle\DataFixtures\ORM\LoadCountryData',
            'AppBundle\DataFixtures\ORM\LoadLocalityData',
            'AppBundle\DataFixtures\ORM\LoadSightTypeData',
            'AppBundle\DataFixtures\ORM\LoadSightData',
            'AppBundle\DataFixtures\ORM\LoadSightTourData',
            'AppBundle\DataFixtures\ORM\LoadSightTicketData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadSightPhotoData',
        ];

        $this->loadFixtures($fixtures);
    }

    public function testGetAllAction()
    {
        $this->client->request('GET', '/api/v1/sight-photos?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(6, $data['sight_photos']);
        $this->comparisonSightPhoto($data['sight_photos'][0]);
    }

    public function testGetAction()
    {
        $sightPhoto = $this->manager->getRepository('AppBundle:SightPhoto')->findOneBy([
            'photoName' => 'kamyanets1.jpg',
        ]);
        $this->client->request('GET', '/api/v1/sight-photos/'.$sightPhoto->getId());

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightPhoto($data['sight_photo']);
    }

    public function testGetSightAction()
    {
        $slug = 'kam-yanec-podilska-fortecya';

        $this->client->request('GET', '/api/v1/sight-photos/sights/'.$slug);

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightPhoto($data['sight_photos'][0]);
    }

    public function testDeleteAction()
    {
        $sightPhoto = $this->manager->getRepository('AppBundle:SightPhoto')->findOneBy([
            'photoName' => 'warszawa1.jpg',
        ]);
        $this->client->request('DELETE', '/api/v1/sight-photos/'.$sightPhoto->getId());

        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $this->client);
    }

    private function comparisonSightPhoto(array $data)
    {
        $sightPhotos = [
            'sight'      => [
                'name'       => 'Кам\'янець-подільська фортеця',
                'sight_type' => [
                    'name' => 'Замок',
                ],
                'locality'   => [
                    'name'    => 'Кам\'янець-Подільський',
                    'country' => [
                        'name' => 'Україна',
                    ],
                ],
            ],
            'photo_name' => 'kamyanets1.jpg',
        ];

        foreach ($sightPhotos as $key => $el) {
            if (is_array($data[$key])) {
                foreach ($data[$key] as $key1 => $el1) {
                    $this->assertEquals($el1, $data[$key][$key1]);
                }
            } else {
                $this->assertEquals($el, $data[$key]);
            }
        }
    }
}
