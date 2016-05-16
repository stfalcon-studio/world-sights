<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Sight;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightRecommendControllerTest extends WebTestCase
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
        $this->client->request('GET', '/api/v1/sight-recommends?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(7, $data['sight_recommends']);
        $this->comparisonSightRecommend($data['sight_recommends'][0]);
        $this->assertEquals(7, $data['_metadata']['total']);
        $this->assertEquals(10, $data['_metadata']['limit']);
        $this->assertEquals(0, $data['_metadata']['offset']);
    }

    public function testGetAction()
    {
        $sightRecommend = $this->manager->getRepository('AppBundle:SightRecommend')->findOneBy([
            'message' => 'Всім рекомендую це місце, краса заворожує',
        ]);

        $this->client->request('GET', '/api/v1/sight-recommends/'.$sightRecommend->getId());

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightRecommend($data['sight_recommend']);
    }

    public function testUserAction()
    {
        $user = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'admin',
        ]);

        $this->client->request('GET', '/api/v1/sight-recommends/users/'.$user->getId().'?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(2, $data['sight_recommends']);
        $this->assertEquals(2, $data['_metadata']['total']);
        $this->assertEquals(10, $data['_metadata']['limit']);
        $this->assertEquals(0, $data['_metadata']['offset']);
    }

    public function testGetSightAction()
    {
        $slug = 'kam-yanec-podilska-fortecya';
        $this->client->request('GET', '/api/v1/sight-recommends/sights/'.$slug.'?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(3, $data['sight_recommends']);
        $this->assertEquals(3, $data['_metadata']['total']);
        $this->assertEquals(10, $data['_metadata']['limit']);
        $this->assertEquals(0, $data['_metadata']['offset']);
    }

    public function testCreateAction()
    {
        /** @var Sight $sight */
        $sight = $this->manager->getRepository('AppBundle:Sight')->findOneBy([
            'slug' => 'ostriv-horticya',
        ]);

        $dataRequest = [
            'message' => 'message',
            'sight'   => $sight->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/sight-recommends',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);
        $this->assertEquals($dataRequest['sight'], $data['sight_recommend']['sight']['id']);
        $this->assertEquals($dataRequest['message'], $data['sight_recommend']['message']);
    }

    public function testUpdateAction()
    {
        $sightRecommend = $this->manager->getRepository('AppBundle:SightRecommend')->findOneBy([
            'message' => 'Розміри бібліотеки зашкалюють',
        ]);

        $dataRequest = [
            'message' => 'Здоровцька бібліотека',
        ];

        $this->client->request(
            'PUT',
            '/api/v1/sight-recommends/'.$sightRecommend->getId(),
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals($dataRequest['message'], $data['sight_recommend']['message']);
    }

    public function testDeleteAction()
    {
        $sightRecommend = $this->manager->getRepository('AppBundle:SightRecommend')->findOneBy([
            'message' => 'Замок прямо у центрі міста, по-моєуму не погано? Усім рекомендую!',
        ]);

        $this->client->request('DELETE', '/api/v1/sight-recommends/'.$sightRecommend->getId());

        $this->assertStatusCode(Response::HTTP_NO_CONTENT, $this->client);
    }

    private function comparisonSightRecommend(array $data)
    {
        $sight = [
            'sight'   => [
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
            ],
            "message" => "Всім рекомендую це місце, краса заворожує",
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

    /**
     * Load fixtrues
     */
    public function getFixtures()
    {
        $fixtures = [
            'AppBundle\DataFixtures\ORM\LoadCountryData',
            'AppBundle\DataFixtures\ORM\LoadLocalityData',
            'AppBundle\DataFixtures\ORM\LoadSightTypeData',
            'AppBundle\DataFixtures\ORM\LoadSightData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadSightRecommendData',
        ];

        $this->loadFixtures($fixtures);
    }
}
