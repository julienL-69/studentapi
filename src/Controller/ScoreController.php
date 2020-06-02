<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/score", name="score_")
 */


class ScoreController extends AbstractController
{
    /**
     * @Route("", name="all", methods={"GET"})
     * this method is use to obtain all the scores of all student
     */
    public function allScores(ScoreRepository $scoreRepository)
    {
        return $this->json($scoreRepository->findAll(), 200, [], ['groups' => 'student:score:read']);
    }

    /**
     * @Route("/average", name="_average_all_students", methods={"GET"})
     * this method is use to obtain the average score of all student's score & number of score registered
     */
    public function findAverageScore(ScoreRepository $scoreRepository)
    {
        return $this->json($scoreRepository->findAverageScore(), 200, []);
    }


    /**
     * @Route("/average/{id}", name="_average_by_student_id", methods={"GET"})
     * this method is use to obtain the average score of a student (by id) and the number of score registered for this student
     */
    public function averageScoreByStudent($id ,ScoreRepository $scoreRepository)
    {
        return $this->json($scoreRepository->findAverageScoreByStudent($id), 200, []);
    }



}
