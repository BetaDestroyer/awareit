<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    /**
     * @Route("/backend/quiz/{quizId}/answer/{questionId}/{answerId}", name="quiz-answer")
     */
    public function saveAnswerAction(Request $request, $quizId, $questionId, $answerId)
    {
    	/* 
    	// get user
    	$user = $this->getUser();

		// get given answer
    	$answers = $this->getDoctrine()
        ->getRepository('AppBundle:Answer')
        ->findByQuestion($questionId);

        $givenAnswer = $this->getDoctrine()
        ->getRepository('AppBundle:Answer')
        ->findById($answerId);

        // Collect ids of answers of question
        $answerIds = array();
        foreach($answers as $answer) {
        	array_push($answerIds, $answer->getId());
        }

        // Überprüfen ob die Antwort zu der gestellten Frage gehört
        // Wenn ja dann Überprüfen ob die Antwort nicht schon gegeben wurde 
        // Wenn nein dann nichts updaten

        // Check if answer is already given

        if (count($user->getAnswers()) == 0) {

        	// check if answer belongs to question
    		if(in_array($answerId, $answerIds) == true) {
    			
    			// set answer for user
		        foreach($givenAnswer as $answer) {
		        	$user->addAnswer($answer);
		        }

		        // Update DB
		        $em = $this->getDoctrine()->getEntityManager(); 
				$em->persist($user); 
				$em->flush(); 

				return new JsonResponse(array('response' => "answer for question added first time"));

    		}

        } else {

        	foreach ($user->getAnswers() as $givenAnswers) {

        		var_dump($givenAnswers->getId());
        		var_dump($answerIds);

	        	if(in_array($givenAnswers->getId(), $answerIds) == false) {

	        		var_dump("123");

	        		// set answer for user
			        foreach($givenAnswer as $answer) {
			        	$user->addAnswer($answer);
			        }

			        // Update DB
			        $em = $this->getDoctrine()->getEntityManager(); 
					$em->persist($user); 
					$em->flush(); 

					return new JsonResponse(array('response' => "answer added"));

	        	}
	    		
	    	}

        }
    	*/

    	return new JsonResponse(array('response' => "nothing added"));
    }

}
