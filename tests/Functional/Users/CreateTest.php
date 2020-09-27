<?php


namespace App\Tests\Functional\Users;

use App\DataFixtures\AppFixtures;
use App\Tests\Functional\ApiTestCase;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class CreateTest extends ApiTestCase
{
    /**
     * @throws JsonException
     */
    public function testEmptyBody(): void
    {
        $this->request('POST', '/users', [], [], [], json_encode([], JSON_THROW_ON_ERROR));

        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('error', $data);
        self::assertIsArray($data['error']);

        $error = $data['error'];
        self::assertArrayHasKey('status', $error);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $error['status']);
        self::assertArrayHasKey('message', $error);
        self::assertEquals('One of required parameters is not set or empty.', $error['message']);
    }

    /**
     * @throws JsonException
     */
    public function testSuccessfulCreate(): void
    {
        $body = json_encode([
            'first_name' => $firstName = 'Tom',
            'last_name' => $lastName = 'Smith',
            'email' => $email = 'test@test.com',
            'password' => 'password'
        ], JSON_THROW_ON_ERROR);

        $this->request('POST', '/users', [], [], [], $body);

        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('id', $data);
        self::assertEquals($firstName, $data['name']['first']);
        self::assertEquals($lastName, $data['name']['last']);
        self::assertEquals($email, $data['email']);
        self::assertArrayNotHasKey('password_hash', $data);
    }

    /**
     * @throws JsonException
     */
    public function testCreateUserWithNotUniqueEmail(): void
    {
        $body = json_encode([
            'first_name' => 'Tom',
            'last_name' => 'Smith',
            'email' => AppFixtures::getUser()['email'],
            'password' => 'password'
        ], JSON_THROW_ON_ERROR);

        $this->request('POST', '/users', [], [], [], $body);

        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('error', $data);
        self::assertIsArray($data['error']);

        $error = $data['error'];
        self::assertArrayHasKey('status', $error);
        self::assertEquals(Response::HTTP_BAD_REQUEST, $error['status']);
        self::assertArrayHasKey('message', $error);
        self::assertEquals('User with this email already exists.', $error['message']);
    }
}