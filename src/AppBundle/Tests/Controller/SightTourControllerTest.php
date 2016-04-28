<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Sight;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * SightTourControllerTest
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightTourControllerTest extends WebTestCase
{
    /** @var Client $client */
    private $client;

    /** @var ObjectManager */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->client  = static::makeClient();
        $this->manager = $this->client->getContainer()->get('doctrine')->getManager();

        $this->getFixtures();
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
        ];

        $this->loadFixtures($fixtures);
    }

    /**
     * Test get all action
     */
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

    /**
     * Test get action
     */
    public function testGetAction()
    {
        $this->client->request('GET', '/api/v1/sight-tours/sightseeing-in-the-city-kamenetz-podolsk');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightTour($data['sight_tour']);
    }

    /**
     * Test create action
     */
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

    /**
     * Test update action
     */
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
            '/api/v1/sight-tours/sightseeing-in-the-city-kamenetz-podolsk',
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

    /**
     * Comparison sight tour with data
     *
     * @param array $data Array of data
     */
    private function comparisonSightTour(array $data)
    {
        $sight = [
            'name'         => 'Екскурсійна програма по місту Кам’янець-Подільському',
            'company_name' => '7 днів',
            'tour_link'    => 'http://www.7dniv.ua/ua/tourism-directions',
            'price'        => 500,
            'slug'         => 'sightseeing-in-the-city-kamenetz-podolsk',
        ];

        foreach ($sight as $key => $el) {
            $this->assertEquals($el, $data[$key]);
        }

        $this->assertEquals('Кам\'янець-подільська фортеця', $data['sight']['name']);
        $this->assertEquals('(03849)2-55-33', $data['sight']['phone']);
    }
}
