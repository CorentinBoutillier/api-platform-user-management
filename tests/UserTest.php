<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var EntityManagerInterface
     */
    protected static $entityManager;

    public function testApiLoginValidAccount() {
        $client = static::createClient();

        $client->request('POST', '/authentication_token',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'],
                '{"email":"client@validated.email", "password":"test"}'
        );

        self::assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        self::assertSame(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        self::assertTrue(isset($responseData['token']));
    }

    public function testApiLoginNotValidAccount() {
        $client = static::createClient();

        $client->request('POST', '/authentication_token',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'],
            '{"email":"not@validated.email", "password":"test"}'
        );

        self::assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        self::assertSame(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('Please confirm your mail address.', $responseData['error']);
    }

    public function testApiLoginDesactivatedAccount() {
        $client = static::createClient();

        $client->request('POST', '/authentication_token',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'],
            '{"email":"client@disabled.email", "password":"test"}'
        );

        self::assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        self::assertSame(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('Desactivated account.', $responseData['error']);
    }

    public function testApiLoginUnknowAccount() {
        $client = static::createClient();

        $client->request('POST', '/authentication_token',
            [], [],
            [
                'CONTENT_TYPE' => 'application/json'],
            '{"email":"unknow@account.email", "password":"test"}'
        );

        self::assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        self::assertSame(401, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('Bad credentials', $responseData['message']);
    }
}
