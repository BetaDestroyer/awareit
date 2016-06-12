<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
	/**
	* @Route("/backend", name="user_backend")
	*/
	public function indexAction(Request $request)
	{
		// replace this example code with whatever you need
        return $this->render('user_backend/dashboard.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
	}

	/**
	* @Route("/backend/dashboard/", name="user_backend_dashboard")
	*/
	public function showDashboardAction(Request $request)
	{
		// replace this example code with whatever you need
        return $this->render('user_backend/dashboard.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
	}

	/**
	* @Route("/backend/manage-users/", name="user_backend_manage_users")
	*/
	public function manageUsersAction(Request $request)
	{
		// Get ID of manager
		$managerId = $this->getUser()->getId();

		// Get group of manager id
		$groups = $this->getUser()->getGroups();

		foreach($groups as $group) {
			$mgGroupId = $group->getId();
		}

		// Get group object
		$groupObj = $this->getDoctrine()
        ->getRepository('AppBundle:Group')
        ->find($mgGroupId);

		// Get users assign to group
        $groupUsers = $groupObj->getUsers();
        $users = array();

        foreach($groupUsers as $key => $user) {
        	$users[$key] = array(
        		"id" => $user->getId(),
        		"username" => $user->getUsername()
    		);
        }

        // Create form to add new users
        

		// replace this example code with whatever you need
        return $this->render('user_backend/manage_users_form.twig.html', array(
        	"users" => $users
    	));
	}

	/**
	* @Route("/backend/manage-users/")
	* @Method({"POST"})
	*/
	public function addUserAction(Request $request)
	{
		$email = $request->query->get('email');
		var_dump($request);

		die();

		return;
	}

}