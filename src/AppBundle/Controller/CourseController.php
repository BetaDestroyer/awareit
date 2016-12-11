<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends Controller
{
    /**
     * @Route("/backend/courses", name="courses")
     */
    public function listCoursesAction(Request $request)
    {
        // Get all courses
    	$courses = $this->getDoctrine()
        ->getRepository('AppBundle:Course')
        ->findByIsActive(1);

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $coursePreviews = array();
        // Collect data for course previews
        foreach($courses as $key => $course) {
        	$coursePreviews[$key]["id"] = $course->getId();
        	$coursePreviews[$key]["name"] = $course->getName();
        	$coursePreviews[$key]["description"] = $course->getDescription();
            $coursePreviews[$key]["test"] = $course->getIsTest();
        	$coursePreviews[$key]["thumbnail"] = $baseurl."/images/thumbnails/".$course->getThumbnail();
        }

        return $this->render('user_backend/courses.html.twig', array(
        	"courses" => $coursePreviews
    	));
    }

    /**
     * @Route("/backend/course/{id}", name="course")
     */
    public function showCourseAction(Request $request, $id)
    {
        // Get course by id
    	$course = $this->getDoctrine()
        ->getRepository('AppBundle:Course')
        ->findOneById($id);

        // Check if current course is testcourse or premium course
        if($course->getIsTest() == 0) {
            $userInfo = $this->get('app.userinfo');
            $hasPayed = $userInfo->hasPayed();

            // If premium course check if group has payed
            if(!$hasPayed) {

                // If group hasnt payed check if its user or manager and redirect with matching message
                $roles = $this->getUser()->getRoles();
                switch ($roles) {
                    case in_array("ROLE_MANAGER", $this->getUser()->getRoles()):
                        $this->addFlash(
                            'info',
                            'Bevor Sie und Ihre Mitarbeiter diesen Kurs absolvieren können, müssen sie ein Paket erwerben.'
                        );

                        return $this->redirectToRoute('user_backend_payment');
                        break;
                    case !in_array("ROLE_MANAGER", $this->getUser()->getRoles()):
                        $this->addFlash(
                            'info',
                            'Bevor Sie diesen Kurs absolvieren können, muss der Manager dieses Accounts ein Paket erwerben.
                            Bei Fragen wenden Sie sich gerne an service@avinga.de.'
                        );

                        return $this->redirectToRoute('courses');
                        break;
                }
                
            }
        }

        $courseInfo = array();
        // Collect data for course info page
    	$courseInfo["id"] = $course->getId();
    	$courseInfo["name"] = $course->getName();
    	$courseInfo["description"] = $course->getDescription();
    	$courseInfo["video"] = $course->getVideo();
    	$courseInfo["quizId"] = $course->getQuiz()->getId();

        return $this->render('user_backend/course.html.twig', array(
        	"course" => $courseInfo
    	));
    }
}
