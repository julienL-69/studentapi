<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\Score;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($p = 0; $p < 50; $p++) {
            $student = new Student;
            $student->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setBirthday($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null));

            $manager->persist($student);

            for ($c = 0; $c < mt_rand(3, 5); $c++) {
                $score = new Score;
                $score->setSubject($faker->jobTitle)
                    ->setScore(rand(0,20))
                    ->setStudent($student);

                $manager->persist($score);
            }
        }

        $manager->flush();
    }
}

