<?php


namespace App\Tests\Functional\Users;

use App\Tests\Functional\ApiTestCase;
use JsonException;

class IndexTest extends ApiTestCase
{
    /**
     * @throws JsonException
     */
    public function testCorrectListOfUsers(): void
    {
        $this->request('GET', '/users');

        $response = $this->client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('items', $data);
        self::assertIsArray($data['items']);
        self::assertNotEmpty($data['items']);
        self::assertArrayHasKey('pagination', $data);

        $user = $data['items'][0];
        self::assertArrayHasKey('id', $user);

        self::assertArrayHasKey('name', $user);
        self::assertIsArray($user['name']);
        self::assertArrayHasKey('first', $user['name']);
        self::assertArrayHasKey('last', $user['name']);
        self::assertArrayHasKey('full', $user['name']);

        self::assertArrayHasKey('email', $user);

        self::assertArrayNotHasKey('password_hash', $user);

        $pagination = $data['pagination'];
        self::assertArrayHasKey('total', $pagination);
        self::assertArrayHasKey('count', $pagination);
        self::assertArrayHasKey('per_page', $pagination);
        self::assertArrayHasKey('page', $pagination);
        self::assertArrayHasKey('pages', $pagination);

        self::assertCount($pagination['count'], $data['items']);
    }
}