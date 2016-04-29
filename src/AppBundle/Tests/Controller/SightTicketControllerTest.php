<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DBAL\Types\SightTicketType;
use AppBundle\Entity\Locality;
use AppBundle\Entity\Sight;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightTicketControllerTest extends WebTestCase
{
    /** @var Client $client */
    private $client;

    /** @var ObjectManager */
    private $manager;

    public function setUp()
    {
        parent::setUp();

        $this->client  = static::makeClient();
        $this->manager = $this->client->getContainer()->get('doctrine')->getManager();

        $this->getFixtures();
    }

    public function testGetAllAction()
    {
        $this->client->request('GET', '/api/v1/sight-tickets');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(5, $data['sight_tickets']);
        $this->comparisonSightTicket($data['sight_tickets'][0]);
    }

    public function testGetAction()
    {
        $this->client->request('GET', '/api/v1/sight-tickets/kiev-kamyanets-train-ticket');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightTicket($data['sight_ticket']);
    }

    public function testCreateAction()
    {
        /** @var Sight $sight */
        /** @var Locality $locality */
        $sight    = $this->manager->getRepository('AppBundle:Sight')->findSightFirstResult();
        $locality = $this->manager->getRepository('AppBundle:Locality')->findLocalityFirstResult();

        $data = [
            'type'     => SightTicketType::BUS_TICKET,
            'link_buy' => 'https://my-ticket',
            'sight'    => $sight->getId(),
            'from'     => $locality->getId(),
            'to'       => $locality->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/sight-tickets',
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
        /** @var Locality $locality */
        $sight    = $this->manager->getRepository('AppBundle:Sight')->findSightFirstResult();
        $locality = $this->manager->getRepository('AppBundle:Locality')->findLocalityFirstResult();

        $data = [
            'type'     => SightTicketType::BUS_TICKET,
            'link_buy' => 'https://my-ticket',
            'sight'    => $sight->getId(),
            'from'     => $locality->getId(),
            'to'       => $locality->getId(),
        ];

        $this->client->request(
            'PUT',
            '/api/v1/sight-tickets/kiev-kamyanets-train-ticket',
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

    public function testDeleteAction()
    {
        $this->client->request('DELETE', '/api/v1/sight-tickets/kiev-kamyanets-train-ticket');

        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $this->client);
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
        ];

        $this->loadFixtures($fixtures);
    }

    private function comparisonSightTicket(array $data)
    {
        $sightTicket = [
            'type'     => SightTicketType::TRAIN_TICKET,
            'link_buy' => 'https://gd.tickets.ua/uk/railwaytracker/table/Kamenetz-Podolsk~2200260',
            'slug'     => 'kiev-kamyanets-train-ticket',
        ];

        foreach ($sightTicket as $key => $el) {
            $this->assertEquals($el, $data[$key]);
        }

        $this->assertEquals('Кам\'янець-подільська фортеця', $data['sight']['name']);
        $this->assertEquals('Київ', $data['from']['name']);
        $this->assertEquals('Кам\'янець-Подільський', $data['to']['name']);
    }
}
