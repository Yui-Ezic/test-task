<?php


namespace App\Tests\Functional\Users;

use App\DataFixtures\AppFixtures;
use App\Tests\Functional\ApiTestCase;
use JsonException;

class ShowTest extends ApiTestCase
{
    /**
     * @throws JsonException
     */
    public function testCorrectUser(): void
    {
        $user = AppFixtures::getUser();
        $this->request('GET', '/users/' . $user['id']);

        $response = $this->client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $data);
        self::assertEquals($user['name']['first'], $data['name']['first']);
        self::assertEquals($user['name']['last'], $data['name']['last']);
        self::assertArrayHasKey('full', $data['name']);
        self::assertEquals($user['email'], $data['email']);
        self::assertArrayNotHasKey('password_hash', $data);
    }
}