<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Entity\Friend;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class FriendControllerTest extends WebTestCase
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
        $this->client->request('GET', '/api/v1/friends?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(2, $data['friends']);
        $this->comparisonAcceptedFriend($data['friends'][0]);
    }

    public function testGetReceivedFriendsAction()
    {
        $this->client->request('GET', '/api/v1/friends/received?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(1, $data['friends']);
    }

    public function testGetRejectedFriendsAction()
    {
        $this->client->request('GET', '/api/v1/friends/rejected?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(1, $data['friends']);
    }

    public function testGetSentFriendsAction()
    {
        $this->client->request('GET', '/api/v1/friends/sent?limit=10&offset=0');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(1, $data['friends']);
    }

    public function testGetAction()
    {
        /** @var Friend $friend */
        $friend = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'admin',
        ]);

        $this->client->request('GET', '/api/v1/friends/'.$friend->getId());

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertArrayHasKey('friend', $data);
        $this->comparisonAcceptedFriend($data['friend']);
    }

    public function testGetFriendStatusAction()
    {
        $this->client->request('GET', '/api/v1/friends/status-types');

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
        $this->assertCount(4, $data['friend_status_types']);
        $this->assertArrayHasKey('SE', $data['friend_status_types']);
        $this->assertArrayHasKey('REC', $data['friend_status_types']);
        $this->assertArrayHasKey('REJ', $data['friend_status_types']);
        $this->assertArrayHasKey('AC', $data['friend_status_types']);
    }

    public function testCreateAction()
    {
        $friend = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'user7',
        ]);

        $data = [
            'friend' => $friend->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/v1/friends',
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->client);
        $this->assertEquals(201, $data['code']);

        $friend = $data['friend'];
        $this->assertEquals('user', $friend['user']['username']);
        $this->assertEquals('user7', $friend['friend']['username']);
        $this->assertEquals(FriendStatusType::SENT, $friend['status']);
    }

    public function testUpdateAction()
    {
        $friend = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'admin',
        ]);

        $data = [
            'status' => FriendStatusType::REJECTED,
        ];

        $this->client->request(
            'PUT',
            '/api/v1/friends/'.$friend->getId(),
            $data,
            [],
            ['Content-Type' => 'application/json'],
            []
        );

        $response = $this->client->getResponse();
        $data     = json_decode($response->getContent(), true);

        $this->assertStatusCode(Response::HTTP_OK, $this->client);
        $this->assertEquals(200, $data['code']);
    }

    public function testDeleteAction()
    {
        $friend = $this->manager->getRepository('AppBundle:User')->findOneBy([
            'username' => 'user5',
        ]);

        $this->client->request('DELETE', '/api/v1/friends/'.$friend->getId());

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
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadFriendData',
        ];

        $this->loadFixtures($fixtures);
    }

    public function comparisonAcceptedFriend(array $data)
    {
        $this->assertEquals('admin', $data['username']);
        $this->assertEquals('admin@gmail.com', $data['email']);
    }
}
