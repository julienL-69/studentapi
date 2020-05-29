<?php


namespace App\Tests;


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    public function testGetAllStudents()
    {
        $client = static::createClient();

        $client->request('GET', '/student');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/student/1');

        var_dump($client->getResponse()->getContent());
    }
}
