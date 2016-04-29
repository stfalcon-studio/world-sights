<?php

namespace AppBundle\Tests\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class CountryControllerTest extends WebTestCase
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

    public function testGetAll()
    {
        $this->client->request('GET', '/api/v1/countries?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(4, $data['countries']);
        $this->comparisonCountries($data['countries'][0]);
    }

    public function testGet()
    {
        $this->client->request('GET', '/api/v1/countries/ukraine');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonCountries($data['country']);
    }

    public function getFixtures()
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

    private function comparisonCountries(array $data)
    {
        $countries = [
            'name' => 'Україна',
            'slug' => 'ukraine',
        ];

        foreach ($countries as $key => $el) {
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
