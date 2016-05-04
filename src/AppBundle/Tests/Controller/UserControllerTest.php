<?php

namespace AppBundle\Tests\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
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

    public function testRegistrationAction()
    {
        $data = [
            'email'    => 'zenmate@gmail.com',
            'username' => 'zenmate',
            'password' => '1234',
        ];

        $this->client->request(
            'POST',
            '/api/v1/users/registration',
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);
        $this->assertEquals($data['user']['username'], 'zenmate');
        $this->assertEquals($data['user']['email'], 'zenmate@gmail.com');
    }

    public function testLoginAction()
    {
        $data = [
            'username' => 'user',
            'password' => '1234',
        ];

        $this->client->request(
            'POST',
            '/api/v1/users/login',
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertArrayHasKey('accessToken', $data);
        $this->assertArrayHasKey('refreshToken', $data);
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
}
