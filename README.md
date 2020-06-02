# Student Api

Project Title : Student API

Technical informations
- symfony V4.9
- php v4.2
- sqlite v2.2.17

Install

    Clone this project
    Run composer install
    Database in sqlite
      - run doctrine to create database, migrate migrations and load fixtures

test with phpunit
To run all tests with php unit, execute ./bin/phpunit in console

This API is made for following student and their scores in school. It is possible to add, modify , remove student and add, edit and remove score of each student and add a field where the score has been obtained.

Possible action and routes :

Students
- retrieve all students
- create a student
- retrieve a student by this id
- retrieve a student by id and all this scores already registered
- modify (update) a student (by this id)
- delete a student (by this id)


Scores
- retrieve the average score in all subject for a student (by this id)
- retrieve the average in all subject for all students

PLEASE READ API MANUEL "API MANUEL.odt"  FOR MORE INFORMATIONS ON "HOW TO USE THESE STUDENT & SCORES  API"






