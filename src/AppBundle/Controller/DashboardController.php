<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Util\TokenGenerator;
use AppBundle\Form\AddEmployeeType;
use AppBundle\Form\GroupType;
use AppBundle\Form\UserType;
use AppBundle\CustomMailer\CustomMailer;

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

        $removeForms = array();
        foreach($groupUsers as $key => $user) {
        	$isManager = 0;
        	if(in_array("ROLE_MANAGER", $user->getRoles())) {
        		$isManager = 1;
        	}

        	$users[$key] = array(
        		"id" => $user->getId(),
        		"username" => $user->getUsername(),
        		"manager" => $isManager
    		); 

        }

        // Create form to add new user
        $addUserForm = $this->createForm(AddEmployeeType::class);
        $addUserForm->handleRequest($request);

        // On save 
        if ($addUserForm->isSubmitted() && $addUserForm->isValid() && $addUserForm->get('save')->isClicked()) {

			// check if is empty || not email
			
			if ( empty($addUserForm->getData()['email']) || filter_var($addUserForm->getData()['email'], FILTER_VALIDATE_EMAIL) == false)  {
				$this->get('session')->getFlashBag()->add('error', 'Feld darf nicht leer sein und muss ein @ beinhalten.');
			    return $this->redirectToRoute('user_backend_manage_users');
			}

	        // Create new user
	        $userManager = $this->get('fos_user.user_manager'); 
			$user = $userManager->createUser(); 
			$user->setEmail($addUserForm->getData()['email']); 

			// Generate random password
			$tokenGenerator = new TokenGenerator;
			$password = $tokenGenerator->generateToken();
    		$password = substr($password, 0, 8);

    		// Set userdata
			$user->setPassword($password); 
    		$user->setPlainPassword($password);
    		$user->addGroup($groupObj);
    		$user->setEnabled(1);

			try {
	    		// Save user to db
	    		$em = $this->getDoctrine()->getEntityManager(); 
				$em->persist($user); 
				$em->flush(); 
			} catch (\Exception $e) { 
				// If user already exists set error to session and return
				if($e->getPrevious()->getCode() == 23000){
					$this->get('session')->getFlashBag()->add('error', sprintf('Es gibt bereits einen Mitarbeiter mit der E-Mail Adresse %s.', $addUserForm->getData()['email']));  
			    }
			    return $this->redirectToRoute('user_backend_manage_users');
			}

			$this->get('session')->getFlashBag()->add('success', sprintf('Der Mitarbeiter wurde erfolgreich angelegt. Es wird eine E-Mail an %s mit den Zugangsdaten gesendet.', $addUserForm->getData()['email']));

			// Send email with random generated password
			$mailer = $this->get('app.custom_mailer');
    		$mailer->sendCustomRegistrationMail($addUserForm->getData()['email'], $password);

	        return $this->redirectToRoute('user_backend_manage_users');
	    }

	    // Create form to delete user
	    if ($addUserForm->isSubmitted() && $addUserForm->isValid() && $addUserForm->get('remove')->isClicked()) {
	    }

		// replace this example code with whatever you need
        return $this->render('user_backend/manage_users_form.twig.html', array(
        	"users" => $users,
        	"add_user_form" => $addUserForm->createView(),
        	"remove_forms" => $removeForms,
    	));
	}

	/**
	* @Route("/backend/manage-users/delete/{id}", name="user_backend_delete_user")
	*/
	public function deleteUserAction(Request $request, $id)
	{
		$userManager = $this->get('fos_user.user_manager'); 
		$user = $userManager->findUserBy(array("id" => $id));

		// Check if user is manager and redirect back with feedback
		if(in_array("ROLE_MANAGER", $user->getRoles())) {
    		$this->get('session')->getFlashBag()->add('error', 'Manager Accounts können nicht über die Mitarbeiterliste gelöscht werden.'); 
    		return $this->redirectToRoute('user_backend_manage_users');
    	}

		// Remove user from group
		$groups = $user->getGroups();
		foreach($groups as $group) {
			$user->removeGroup($group);
		}

		$em = $this->getDoctrine()->getEntityManager(); 
		$em->persist($user); 
		$em->flush(); 

		// Remove user completely
		$userManager->deleteUser($user);

		return $this->redirectToRoute('user_backend_manage_users');
	}

}