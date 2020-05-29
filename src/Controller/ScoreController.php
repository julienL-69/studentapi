<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/score", name="score_")
 */


class ScoreController extends AbstractController
{
    /**
     * @Route("", name="all", methods={"GET"})
     */
    public function allScores(ScoreRepository $scoreRepository)
    {
        return $this->json($scoreRepository->findAll(), 200, [], ['groups' => 'student:score:read']);
    }



}
