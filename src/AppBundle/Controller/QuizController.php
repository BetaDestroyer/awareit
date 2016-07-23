<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\UserQuiz;

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
     * @Route("/backend/quiz/{quizId}/answer/{questionId}/{answerId}/{counterId}", name="quiz-answer")
     */
    public function saveAnswerAction(Request $request, $quizId, $questionId, $answerId, $counterId)
    {
    	// get user
    	$user = $this->getUser();

    	// get quiz
    	$quizes = $this->getDoctrine()
        ->getRepository('AppBundle:Quiz')
        ->findById($quizId);

        if( empty($quizes) ) {
        	return new JsonResponse(array('message' => "There is no Quiz with such an ID"), 401);
        }

        // Check if there is a UserQuiz Entity for current user and quiz
		$userQuiz = $this->getDoctrine()
        ->getRepository('AppBundle:UserQuiz')
        ->findOneBy(array(
			'user' => $user->getId(),
			'quiz' => $quizId,
		));

		if( $userQuiz == null ) {
			// Create new UserQuiz relation data
	        $userQuiz = new UserQuiz();
	        $userQuiz->setUser($user);
	        $userQuiz->setquizComplete(FALSE);

	        foreach($quizes as $quiz) {
	        	$userQuiz->setQuiz($quiz);
	        }
		}

        $userQuiz->setCounter($counterId);

        // Update DB
        $em = $this->getDoctrine()->getEntityManager(); 
		$em->persist($userQuiz); 
		$em->flush(); 

		// get answers of question
    	$answersOfQuestion = $this->getDoctrine()
        ->getRepository('AppBundle:Answer')
        ->findByQuestion($questionId);

		// Überprüfen ob die Antwort zu der gestellten Frage gehört
        if( empty($answersOfQuestion) ) {
        	return new JsonResponse(array('message' => "The given answer do not belong to this question"), 401);
        }

        // Überprüfen ob eine Antwort auf die Frage gegeben ist 

        // Hole alle Antworten von user 
        $allAnswersOfUser = $user->getAnswers();

        // Für jede Antwort der Frage
        foreach($answersOfQuestion as $answerOfQuestion) {

        	// Für jede Antwort des users
        	foreach($allAnswersOfUser as $answerOfUser) {

        		// Prüfe ob eine der Antworten des Users zu eine der Antworten der Frage gegeben ist 
        		if( $answerOfUser->getId() == $answerOfQuestion->getId()) {
        			return new JsonResponse(array('message' => "An answer for this Question is already given"), 401);
        		}

        	}

        }

        // Antwort speichern

        // hole Antwort Object anhand gegebener ID
        $givenAnswer = $this->getDoctrine()
        ->getRepository('AppBundle:Answer')
        ->findById($answerId);

        if( empty($givenAnswer) ) {
        	return new JsonResponse(array('message' => "There is no such answer with given ID"), 401);
        }

        // Antwort Object mit User Object verknüpfen
        foreach($givenAnswer as $answer) {
        	$user->addAnswer($answer);
        }

        // Update DB
        $em = $this->getDoctrine()->getEntityManager(); 
		$em->persist($user); 
		$em->flush(); 

    	return new JsonResponse(array('response' => "Given answer saved to DB"));
    }

    /**
     * @Route("/backend/quiz/{quizId}/counter/", name="get-quiz-counter")
     */
    public function getCounterAction(Request $request, $quizId)
    {
    	// get user
    	$user = $this->getUser();

    	$quizOfUser = $this->getDoctrine()
        ->getRepository('AppBundle:UserQuiz')
        ->findOneBy(array(
			'user' => $user->getId(),
			'quiz' => $quizId,
		));

		if( $quizOfUser == null ) {
			$json = json_encode(0);
			return new JsonResponse(array('response' => $json));
		}

		$counter = $quizOfUser->getCounter();

		$json = json_encode($counter);

		return new JsonResponse(array('response' => $json));
    }

}
