<?php

namespace Acme\DemoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testgetAllUsersIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $_SERVER['HOSTNAME'] . '/v1/users/1/visits');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("user_id")')->count());
    }

    public function testPostUserVisitIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', $_SERVER['HOSTNAME'] . '/v1/users/1/visits', array());

        // Submit a raw JSON string in the request body
        $client->request(
                'POST',
                '/v1/users/1/visits',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{"city": "Chicago","state": "IL"}'
            );

        $this->assertGreaterThan(0, $crawler->filter('html:contains("status")')->count());

    }
}
