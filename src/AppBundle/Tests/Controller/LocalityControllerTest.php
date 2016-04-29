<?php

namespace AppBundle\Tests\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class LocalityControllerTest extends WebTestCase
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
        $this->client->request('GET', '/api/v1/localities?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(7, $data['localities']);
        $this->comparisonLocality($data['localities'][0]);
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

    private function comparisonLocality(array $data)
    {
        $locality = [
            'name'    => 'Кам\'янець-Подільський',
            'slug'    => 'kamyanets',
            'country' => [
                'name' => 'Україна',
                'slug' => 'kamyanets',
            ],
        ];

        foreach ($locality as $key => $el) {
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
