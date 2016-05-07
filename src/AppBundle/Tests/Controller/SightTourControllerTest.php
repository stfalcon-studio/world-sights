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
        $this->client->request('GET', '/api/v1/sight-tours');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(4, $data['sight_tours']);
        $this->comparisonSightTour($data['sight_tours'][0]);
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

        $data = [
            'name'         => '3 дні у Празі',
            'company_name' => 'Аккорд',
            'tour_link'    => 'http://www.accord.com.ua/tour-y-pragy',
            'price'        => 5000,
            'sight'        => $sight->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/sight-tours',
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);

        foreach ($data as $key => $element) {
            $this->assertEquals($element, $data[$key]);
        }
    }

    public function testUpdateAction()
    {
        /** @var Sight $sight */
        $sight = $this->manager->getRepository('AppBundle:Sight')->findSightFirstResult();

        $data = [
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
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);

        foreach ($data as $key => $element) {
            $this->assertEquals($element, $data[$key]);
        }
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
