<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student_all", methods={"GET"})
     */
    public function allStudents(StudentRepository $studentRepository)
    {
        return $this->json($studentRepository->findAll(), 200, [], ['groups' => 'students:read']);
    }

    /**
     * @Route("/student", name="student_store" , methods={"POST"})
     */

    public function store(Request $request, SerializerInterface $serializer,
                          EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonPost = $request->getContent();

        try {
            $student = $serializer->deserialize($jsonPost, Student::class, 'json');

            $errors = $validator->validate($student);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($student);
            $em->flush();
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

        return $this->json($student, 201, [], ['groups' => 'students:read']);
    }

    /**
     * @Route("/student/{id}", name="student_by_id", methods={"GET"})
     */
    public function student($id, StudentRepository $studentRepository)
    {
        $student = $studentRepository->findOneBy(['id' => $id]);

        if ($student == null) {
            return $this->json([
                'status' => 404,
                'message' => 'student not found'
            ], 404);
        }

        return $this->json($student, 200, [], ['groups' => 'students:read']);
    }

    /**
     * @Route("/student/{id}", name="student_delete_by_id", methods={"DELETE"})
     */
    public function studentDelete($id, StudentRepository $studentRepository, EntityManagerInterface $em)
    {
        $student = $studentRepository->findOneBy(['id' => $id]);

        if ($student == null) {
            return $this->json([
                'status' => 404,
                'message' => 'student not found'
            ], 404);
        }

        try {
            $em->remove($student);
            $em->flush();
        } catch (Exception $e) {
            return $this->json([
                'status' => 405,
                'message' => 'Not allowed: you can not delete this student'
            ], 405);
        }

        return $this->json([
            'status' => 200,
            'message' => 'student delete'
        ], 200);

    }

    /**
     * @Route("/student/{id}", name="update_student_by_id", methods={"PUT"})
     */
    public function studentUpdate($id, Request $request, StudentRepository $studentRepository,
                                  SerializerInterface $serializer, EntityManagerInterface $em,
                                  ValidatorInterface $validator)
    {
        $student = $studentRepository->findOneBy(['id' => $id]);
        $jsonPost = $request->getContent();


        if ($student == null) {
            return $this->json([
                'status' => 404,
                'message' => 'student not found'
            ], 404);
        }

        $updateData = json_decode($jsonPost, true);


        $student->setLastname($updateData['lastname']);
        $student->setFirstname($updateData['firstname']);

        // test if date format send is ok ("Y/M/d") if object date time send error "date format code 400"
        try {
            $birthdayDate = new DateTime($updateData['birthday']);
            $student->setBirthday($birthdayDate);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 400,
                'message' => 'date format must be Y/M/D'
            ], 400);
        }

        //validation of data before persist and flusch
        try {
            $errors = $validator->validate($student);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($student);
            $em->flush();
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

        //return lastname, firstname, birthday thank to groups parameters
        return $this->json($student, 201, [], ['groups' => 'students:read']);
    }


    /**
     * @Route("/student/{id}/score", name="student_score_by_id", methods={"GET"})
     * @param $id
     * @param StudentRepository $studentRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function studentScores($id, StudentRepository $studentRepository)
    {
        $student = $studentRepository->findOneBy(['id' => $id]);

        if ($student == null) {
            return $this->json([
                'status' => 404,
                'message' => 'student not found'
            ], 404);
        }

        return $this->json($student, 200, [], ['groups' => 'student:score:read']);
    }


}
