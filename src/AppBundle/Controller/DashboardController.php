<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
		// replace this example code with whatever you need
        return $this->render('user_backend/manage_users_form.twig.html', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
	}

}