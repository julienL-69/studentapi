<?php

//./bin/phpunit

namespace App\Tests;


namespace App\Tests\Controller;

use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentControllerTest extends WebTestCase
{
    public function testGetStudents()
    {
        $client = static::createClient();

        //Verify if routes  to get Allstudent' work and send code 200
        $client->request('GET', '/student');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Verify if routes 'to get a student by this id' work and send code 200
        $client->request('GET', '/student/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Verify if creation of student work and send code 201 and if json send by the api is the same then the ones sent
        // this student will be used to make the test for the Creation, the update, adding score and check average code then delete
        $studentToCreate = '{"firstname":"Fabien" , "lastname" :"Pelous", "birthday" :"1979-10-22T00:00:00+01:00"}';
        //objet student equivalent to json send in the next request
        $studentToCreateInArray = json_decode($studentToCreate, true);

        // request creation of a new student
        $client->request(
            'POST',
            '/student',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $studentToCreate
        );

        //retrieve of json and create student object with it
        $studentCreated = json_decode($client->getResponse()->getContent());
        // transformation of the json in array
        $studentCreated = json_decode(json_encode($studentCreated), true);

        //store of the id of the new student to test (update and delete)
        $idCreated = $studentCreated['id'];
        //Creation of the 2 objects Student ( studentToCreate and StudentCreated) , the id will be the same
        $studentObjectToCreate = new Student();
        $studentObjectToCreate->setId($studentCreated['id']);
        $studentObjectToCreate->setLastname($studentToCreateInArray['lastname']);
        $studentObjectToCreate->setFirstname($studentToCreateInArray['firstname']);
        $studentObjectToCreate->setBirthday(new \DateTime($studentToCreateInArray['birthday']));

        $studentObjectCreated = new Student();
        $studentObjectCreated->setId($studentCreated['id']);
        $studentObjectCreated->setLastname($studentCreated['lastname']);
        $studentObjectCreated->setFirstname($studentCreated['firstname']);
        $studentObjectCreated->setBirthday(new \DateTime($studentCreated['birthday']));

        //Verify if the 2 objects are identical
        $this->assertEquals($studentObjectToCreate, $studentObjectCreated);

        // Verify if creation code status is 201
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        //Test to validate update of student, we modify the student created for last test thank to the id
        $studentToUpdate = '{"firstname":"julien" , "lastname" :"Lardant", "birthday" :"1979-10-22T00:00:00+01:00"}';
        //objet student equivalent to json send in the next request
        $studentToUpdateInArray = json_decode($studentToUpdate, true);

        $client->request(
            'PUT',
            '/student/' . $idCreated,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $studentToUpdate
        );

        //creation of the student object returned by the query and cretaion of the student object of the data sent in the request PUT
        $studentUpdated = json_decode($client->getResponse()->getContent());
        $studentUpdated = json_decode(json_encode($studentUpdated), true);

        $studentObjectToUpdate = new Student();
        $studentObjectToUpdate->setId($idCreated);
        $studentObjectToUpdate->setLastname($studentToUpdateInArray['lastname']);
        $studentObjectToUpdate->setFirstname($studentToUpdateInArray['firstname']);
        $studentObjectToUpdate->setBirthday(new \DateTime($studentToUpdateInArray['birthday']));


        $studentObjectUpdated = new Student();
        $studentObjectUpdated->setId($idCreated);
        $studentObjectUpdated->setLastname($studentUpdated['lastname']);
        $studentObjectUpdated->setFirstname($studentUpdated['firstname']);
        $studentObjectUpdated->setBirthday(new \DateTime($studentUpdated['birthday']));

        //Verify if the 2 objects are identical
        $this->assertEquals($studentObjectToUpdate, $studentObjectUpdated);

        // Verify if creation code status is 201
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        //Add  10 scores to the update Student to test if the average of score is right
        $sumOfScore = 0;
        for ($i = 0; $i < 10; $i++) {
            $score = rand(0, 20);
            $sumOfScore += $score;
            $scoreToAdd = '{ "subject": "test","score":' . $score . '}';
            $client->request(
                'POST',
                '/student/' . $idCreated . '/addscore',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                $scoreToAdd
            );
        }

        $averageScore = $sumOfScore / 10;

        //retrieve the average score of the student
        $averageScoreResponse = $client->request('GET', '/score/average/' . $idCreated);
        $averageScoreResponse = json_decode($client->getResponse()->getContent());
        $averageScoreResponse = json_decode(json_encode($averageScoreResponse), true);

        $this->assertEquals($averageScore, $averageScoreResponse[0]['average']);

        //Test delete student (the one we create and update)
        $client->request('DELETE', '/student/' . $idCreated);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}
