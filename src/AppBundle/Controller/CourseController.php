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
        	$coursePreviews[$key]["thumbnail"] = $baseurl."/images/thumbnails/".$course->getThumbnail();
        }

        return $this->render('user_backend/courses.html.twig', array(
        	"courses" => $coursePreviews
    	));
    }
}
