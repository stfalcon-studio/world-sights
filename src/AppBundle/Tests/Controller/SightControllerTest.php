<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DBAL\Types\SightTicketType;
use AppBundle\Entity\Locality;
use AppBundle\Entity\SightType;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightControllerTest extends WebTestCase
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

    public function testGetAllAction()
    {
        $this->client->request('GET', '/api/v1/sights?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(6, $data['sights']);
        $this->comparisonSight($data['sights'][0]);
        $this->assertEquals(6, $data['_metadata']['total']);
        $this->assertEquals(10, $data['_metadata']['limit']);
        $this->assertEquals(0, $data['_metadata']['offset']);
    }

    public function testGetAction()
    {
        $this->client->request('GET', '/api/v1/sights/kam-yanec-podilska-fortecya');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSight($data['sight']);
    }

    public function testGetTicketAction()
    {
        $this->client->request('GET', '/api/v1/sights/kam-yanec-podilska-fortecya/tickets');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightTicket($data['sight_tickets'][0]);
    }

    public function testGetTourAction()
    {
        $this->client->request('GET', '/api/v1/sights/kam-yanec-podilska-fortecya/tours');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightTour($data['sight_tours'][0]);
    }

    public function testCreateAction()
    {
        /** @var SightType $sightType */
        /** @var Locality $locality */
        $sightType = $this->manager->getRepository('AppBundle:SightType')->findSightTypeFirstResult();
        $locality  = $this->manager->getRepository('AppBundle:Locality')->findLocalityFirstResult();

        $dataRequest = [
            'name'       => 'Кам\'яна фортеця',
            'phone'      => '(03849)2-55-33',
            'website'    => 'http://muzeum.in.ua/',
            'longitude'  => 26.563411,
            'latitude'   => 48.67351,
            'sight_type' => $sightType->getId(),
            'locality'   => $locality->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/sights',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);
        $this->assertEquals($dataRequest['name'], $data['sight']['name']);
        $this->assertEquals($dataRequest['phone'], $data['sight']['phone']);
        $this->assertEquals($dataRequest['sight_type'], $data['sight']['sight_type']['id']);
        $this->assertEquals($dataRequest['locality'], $data['sight']['locality']['id']);
    }

    public function testUpdateAction()
    {
        /** @var SightType $sightType */
        /** @var Locality $locality */
        $sightType = $this->manager->getRepository('AppBundle:SightType')->findSightTypeFirstResult();
        $locality  = $this->manager->getRepository('AppBundle:Locality')->findLocalityFirstResult();

        $dataRequest = [
            'name'       => 'Кам\'яна фоssdfртеця',
            'phone'      => '(03849)2-55-4433',
            'website'    => 'http://muzeumvv.in.ua/',
            'longitude'  => 26.56,
            'latitude'   => 48.61,
            'sight_type' => $sightType->getId(),
            'locality'   => $locality->getId(),
        ];

        $this->client->request(
            'PUT',
            '/api/v1/sights/hotinska-fortecya',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals($dataRequest['name'], $data['sight']['name']);
        $this->assertEquals($dataRequest['phone'], $data['sight']['phone']);
        $this->assertEquals($dataRequest['sight_type'], $data['sight']['sight_type']['id']);
        $this->assertEquals($dataRequest['locality'], $data['sight']['locality']['id']);
    }

    public function testDeleteAction()
    {
        $this->client->request('DELETE', '/api/v1/sights/hotinska-fortecya');

        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $this->client);
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
        ];

        $this->loadFixtures($fixtures);
    }

    private function comparisonSight(array $data)
    {
        $sight = [
            'name'       => 'Кам\'янець-подільська фортеця',
            'phone'      => '(03849)2-55-33',
            'website'    => 'http://muzeum.in.ua/',
            'slug'       => 'kam-yanec-podilska-fortecya',
            'sight_type' => [
                'name' => 'Замок',
            ],
            'locality'   => [
                'name'    => 'Кам\'янець-Подільський',
                'country' => [
                    'name' => 'Україна',
                ],
            ],
        ];

        foreach ($sight as $key => $el) {
            if (is_array($data[$key])) {
                foreach ($data[$key] as $key1 => $el1) {
                    $this->assertEquals($el1, $data[$key][$key1]);
                }
            } else {
                $this->assertEquals($el, $data[$key]);
            }
        }
    }

    private function comparisonSightTour(array $data)
    {
        $sightTour = [
            'name'         => 'Екскурсійна програма по місту Кам’янець-Подільському',
            'company_name' => '7 днів',
            'tour_link'    => 'http://www.7dniv.ua/ua/tourism-directions',
            'slug'         => 'ekskursiyna-programa-po-mistu-kam-yanec-podilskomu',
        ];

        foreach ($sightTour as $key => $el) {
            $this->assertEquals($el, $data[$key]);
        }
    }

    private function comparisonSightTicket(array $data)
    {
        $sightTour = [
            'type'     => SightTicketType::TRAIN_TICKET,
            'link_buy' => 'https://gd.tickets.ua/uk/railwaytracker/table/Kamenetz-Podolsk~2200260',
            'slug'     => 'kijiv-kam-yanec-podilskiy-tt',
        ];

        foreach ($sightTour as $key => $el) {
            $this->assertEquals($el, $data[$key]);
        }
    }
}
