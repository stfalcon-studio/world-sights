<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightVisit;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightVisitControllerTest extends WebTestCase
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
            'AppBundle\DataFixtures\ORM\LoadFriendData',
            'AppBundle\DataFixtures\ORM\LoadSightVisitData',
        ];

        $this->loadFixtures($fixtures);
    }

    public function testGetAllAction()
    {
        $this->client->request('GET', '/api/v1/sight-visits?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(3, $data['sight_visits']);
        $this->comparisonSightVisit($data['sight_visits'][0]);
        $this->assertEquals(3, $data['_metadata']['total']);
        $this->assertEquals(10, $data['_metadata']['limit']);
        $this->assertEquals(0, $data['_metadata']['offset']);
    }

    public function testGetAllFriendsAction()
    {
        $this->client->request('GET', '/api/v1/sight-visits/friends?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(2, $data['sight_visits']);
        $this->comparisonSightVisitFriend($data['sight_visits'][1]);
    }

    public function testGetAction()
    {
        $sight = $this->manager->getRepository('AppBundle:Sight')->findOneBy([
            'slug' => 'korolivskiy-zamok-u-varshavi',
        ]);

        $this->client->request('GET', '/api/v1/sight-visits/'.$sight->getId());

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightVisit($data['sight_visit']);
    }

    public function testFriendAction()
    {
        $friend = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'admin',
        ]);

        $this->client->request('GET', '/api/v1/sight-visits/friends/'.$friend->getId());

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightVisitFriend($data['sight_visits'][0]);
    }

    public function testCreateAction()
    {
        $sight = $this->manager->getRepository('AppBundle:Sight')->findOneBy([
            'name' => 'острів Хортиця',
        ]);
        $dataRequest  = [
            'sight' => $sight->getId(),
            'date'  => '2015-06-05 13:00',
        ];

        $this->client->request(
            'POST',
            '/api/v1/sight-visits',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);
        /** @var Sight $sightVisit */
        $sightVisit = $data['sight_visit'];

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);
        $this->assertEquals('острів Хортиця', $sightVisit['name']);
        $this->assertEquals('ostriv-horticya', $sightVisit['slug']);
        $this->assertEquals('Острів', $sightVisit['sight_type']['name']);
        $this->assertEquals('Запоріжжя', $sightVisit['locality']['name']);
        $this->assertEquals('Україна', $sightVisit['locality']['country']['name']);
    }

    public function testUpdateAction()
    {
        $sight = $this->manager->getRepository('AppBundle:Sight')->findOneBy([
            'slug' => 'kam-yanec-podilska-fortecya',
        ]);

        $data = [
            'date' => '2015-06-05 13:00',
        ];

        $this->client->request(
            'PUT',
            '/api/v1/sight-visits/'.$sight->getId(),
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);
        /** @var Sight $sightVisit */
        $sightVisit = $data['sight_visit'];

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals('Кам\'янець-подільська фортеця', $sightVisit['name']);
        $this->assertEquals('kam-yanec-podilska-fortecya', $sightVisit['slug']);
        $this->assertEquals('Замок', $sightVisit['sight_type']['name']);
        $this->assertEquals('Кам\'янець-Подільський', $sightVisit['locality']['name']);
        $this->assertEquals('Україна', $sightVisit['locality']['country']['name']);
    }

    public function testDeleteAction()
    {
        $sight = $this->manager->getRepository('AppBundle:Sight')->findSightFirstResult();

        $this->client->request('DELETE', '/api/v1/sight-visits/'.$sight->getId());

        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $this->client);
    }

    public function comparisonSightVisit(array $data)
    {
        $sightVisit = [
            'name'       => 'Королівський замок у Варшаві',
            'slug'       => 'korolivskiy-zamok-u-varshavi',
            'sight_type' => [
                'name' => 'Замок',
            ],
            'locality'   => [
                'country' => [
                    'name' => 'Польща',
                ],
            ],
        ];

        foreach ($sightVisit as $key => $el) {
            if (is_array($data[$key])) {
                foreach ($data[$key] as $key1 => $el1) {
                    $this->assertEquals($el1, $data[$key][$key1]);
                }
            } else {
                $this->assertEquals($el, $data[$key]);
            }
        }
    }

    public function comparisonSightVisitFriend(array $data)
    {
        $sightVisit = [
            'name'       => 'Королівський замок у Варшаві',
            'slug'       => 'korolivskiy-zamok-u-varshavi',
            'sight_type' => [
                'name' => 'Замок',
            ],
            'locality'   => [
                'country' => [
                    'name' => 'Польща',
                ],
            ],
        ];

        foreach ($sightVisit as $key => $el) {
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
