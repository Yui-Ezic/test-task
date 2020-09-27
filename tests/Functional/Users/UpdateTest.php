<?php


namespace App\Tests\Functional\Users;

use App\DataFixtures\AppFixtures;
use App\Entity\User\User;
use App\Tests\Functional\ApiTestCase;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class UpdateTest extends ApiTestCase
{
    /**
     * @throws JsonException
     */
    public function testEmptyBody(): void
    {
        $user = AppFixtures::getUser();
        $this->request('PUT', '/users/' . $user['id'], [], [], [], json_encode([], JSON_THROW_ON_ERROR));

        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertTrue($data['success']);
    }

    /**
     * @throws JsonException
     */
    public function testChangeFirstName(): void
    {
        $user = $this->getUser();
        $body = json_encode([
            'first_name' => $name = 'New',
        ], JSON_THROW_ON_ERROR);

        $this->request('PUT', '/users/' . $user->getId()->getValue(), [], [], [], $body);

        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertTrue($data['success']);

        self::assertEquals($name, $this->getUser()->getName()->getFirst());
    }

    /**
     * @throws JsonException
     */
    public function testSetToUserNotUniqueEmail(): void
    {
        $user = AppFixtures::getUser();
        $body = json_encode([
            'email' => $user['email'],
        ], JSON_THROW_ON_ERROR);

        $this->request('PUT', '/users/' . $user['id'], [], [], [], $body);

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

    private function getUser(): User
    {
        return $this->em->getRepository(User::class)->find(AppFixtures::getUser()['id']);
    }
}