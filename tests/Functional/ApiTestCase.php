<?php


namespace App\Tests\Functional;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ApiTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->em->getConnection()->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollback();
        $this->em->close();
        parent::tearDown();
    }

    protected function request(string $method, string $uri, array $parameters = [], array $files = [],
                               array $server = [], string $content = null, bool $changeHistory = true): Crawler
    {
        $server = array_merge($server, [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        return $this->client->request($method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }
}