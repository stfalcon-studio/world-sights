<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Sight;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightTourControllerTest extends WebTestCase
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

        $this->manager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->client->setServerParameter('HTTP_X_AUTH_TOKEN', '1e5008f3677f7ba2a8bd8e47b8c0c6');
    }

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

    public function testGetAllAction()
    {
        $this->client->request('GET', '/api/v1/sight-tours?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(4, $data['sight_tours']);
        $this->comparisonSightTour($data['sight_tours'][0]);
        $this->assertEquals(4, $data['_metadata']['total']);
        $this->assertEquals(10, $data['_metadata']['limit']);
        $this->assertEquals(0, $data['_metadata']['offset']);
    }

    public function testGetAction()
    {
        $this->client->request('GET', '/api/v1/sight-tours/ekskursiyna-programa-po-mistu-kam-yanec-podilskomu');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightTour($data['sight_tour']);
    }

    public function testCreateAction()
    {
        /** @var Sight $sight */
        $sight = $this->manager->getRepository('AppBundle:Sight')->findSightFirstResult();

        $dataRequest = [
            'name'         => '3 дні у Празі',
            'company_name' => 'Аккорд',
            'tour_link'    => 'http://www.accord.com.ua/tour-y-pragy',
            'price'        => 5000,
            'sight'        => $sight->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/sight-tours',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);
        $this->assertEquals($dataRequest['name'], $data['sight_tour']['name']);
        $this->assertEquals($dataRequest['company_name'], $data['sight_tour']['company_name']);
        $this->assertEquals($dataRequest['price'], $data['sight_tour']['price']);
        $this->assertEquals($dataRequest['sight'], $data['sight_tour']['sight']['id']);
    }

    public function testUpdateAction()
    {
        /** @var Sight $sight */
        $sight = $this->manager->getRepository('AppBundle:Sight')->findSightFirstResult();

        $dataRequest = [
            'name'         => '4 дні у Празі',
            'company_name' => '5-Аккорд',
            'company_link' => 'http://www.one.com',
            'tour_link'    => 'http://www.5accord.com.ua/tour-y-pragy',
            'price'        => 600,
            'sight'        => $sight->getId(),
        ];

        $this->client->request(
            'PUT',
            '/api/v1/sight-tours/ekskursiyna-programa-po-mistu-kam-yanec-podilskomu',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals($dataRequest['name'], $data['sight_tour']['name']);
        $this->assertEquals($dataRequest['company_name'], $data['sight_tour']['company_name']);
        $this->assertEquals($dataRequest['price'], $data['sight_tour']['price']);
        $this->assertEquals($dataRequest['sight'], $data['sight_tour']['sight']['id']);
    }

    private function comparisonSightTour(array $data)
    {
        $sight = [
            'name'         => 'Екскурсійна програма по місту Кам’янець-Подільському',
            'company_name' => '7 днів',
            'price'        => 500,
            'slug'         => 'ekskursiyna-programa-po-mistu-kam-yanec-podilskomu',
            'sight'        => [
                'name'       => 'Кам\'янець-подільська фортеця',
                'phone'      => '(03849)2-55-33',
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
}
