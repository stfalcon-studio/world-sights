<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Sight;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class SightReviewControllerTest extends WebTestCase
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
        $this->client->request('GET', '/api/v1/sight-reviews?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(7, $data['sight_reviews']);
        $this->comparisonSightReviews($data['sight_reviews'][0]);
    }

    public function testGetAction()
    {
        $sightReview = $this->manager->getRepository('AppBundle:SightReview')->findOneBy([
            'topic' => 'Чудовий Кам\'янецький замок',
        ]);

        $this->client->request('GET', '/api/v1/sight-reviews/'.$sightReview->getId());

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->comparisonSightReviews($data['sight_review']);
    }

    public function testGetSightAction()
    {
        $slug = 'kam-yanec-podilska-fortecya';

        $this->client->request('GET', '/api/v1/sight-reviews/sights/'.$slug.'?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(3, $data['sight_reviews']);
        $this->comparisonSightReviews($data['sight_reviews'][0]);
    }

    public function testGetUserAction()
    {
        $user = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'admin',
        ]);

        $this->client->request('GET', '/api/v1/sight-reviews/users/'.$user->getId().'?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(3, $data['sight_reviews']);
    }

    public function testCreateAction()
    {
        /** @var Sight $sightType */
        $sight = $this->manager->getRepository('AppBundle:Sight')->findOneBy([
            'slug' => 'ostriv-horticya',
        ]);

        $dataRequest = [
            'topic'       => 'topic',
            'description' => 'message',
            'mark'        => 4,
            'sight'       => $sight->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/sight-reviews',
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);
        $this->assertEquals($dataRequest['topic'], $data['sight_review']['topic']);
        $this->assertEquals($dataRequest['mark'], $data['sight_review']['mark']);
        $this->assertEquals($dataRequest['sight'], $data['sight_review']['sight']['id']);
    }

    public function testUpdateAction()
    {
        $sightReview = $this->manager->getRepository('AppBundle:SightReview')->findOneBy([
            'topic' => 'Неймовірна бібліотека у столиці Білорусі',
        ]);

        $dataRequest = [
            'mark' => 2,
        ];

        $this->client->request(
            'PUT',
            '/api/v1/sight-reviews/'.$sightReview->getId(),
            $dataRequest,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals($dataRequest['mark'], $data['sight_review']['mark']);
    }

    public function testDeleteAction()
    {
        $sightReview = $this->manager->getRepository('AppBundle:SightReview')->findOneBy([
            'topic' => 'Замок у самому центрі Варшами',
        ]);

        $this->client->request('DELETE', '/api/v1/sight-reviews/'.$sightReview->getId());

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
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadSightReviewData',
        ];

        $this->loadFixtures($fixtures);
    }

    private function comparisonSightReviews(array $data)
    {
        $sight = [
            'sight' => [
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
            "topic" => "Чудовий Кам'янецький замок",
            "mark"  => 5,
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
