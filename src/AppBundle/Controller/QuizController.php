<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends Controller
{
    /**
     * @Route("/backend/quiz/{id}", name="quiz")
     */
    public function renderQuizAction(Request $request, $id)
    {
        // Get Quiz by ID
    	$quiz = $this->getDoctrine()
        ->getRepository('AppBundle:Quiz')
        ->findById($id);

        // Get Data of Quiz
        $quizData = array();
        foreach($quiz as $data) {
        	$quizId = $data->getId(); 

        	$quizData["id"] = $quizId;
        	$quizData["name"] = $data->getName();
        }

        // Get Questions of Quiz
        $questions = $this->getDoctrine()
        ->getRepository('AppBundle:Question')
        ->findByQuiz($id);

        // Get Data of Questions
        $questionsData = array();
        $questionIds = array();
        foreach($questions as $key => $question) {
        	$questionId = $question->getId();
        	$questionsData[$questionId]["id"] = $question->getId();
        	$questionsData[$questionId]["questionText"] = $question->getQuestionText();
        	$questionsData[$questionId]["points"] = $question->getPoints();

        	// Build extra array to fetch all answers at one query
        	$questionIds[] = $questionId;
        }

        // Get Answers of Questions
        $answers = $this->getDoctrine()
        ->getRepository('AppBundle:Answer')
        ->findByQuestion($questionIds);

        // Get Data of Answers and merge it with Question Array
        foreach($answers as $key => $answer) {

        	$questionsData[$answer->getQuestion()->getId()]["answers"][$key] = array(
        		"id" => $answer->getId(),
        		"answerText" => $answer->getAnswerText(),
        		"isCorrect" => $answer->getIsCorrect(),
    		);

        }

        return $this->render('user_backend/quiz.html.twig', array(
        	"quizInfo" => $quizData,
        	"quizData" => $questionsData
    	));
    }

}
