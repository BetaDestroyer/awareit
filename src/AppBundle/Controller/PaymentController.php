<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;

class PaymentController extends Controller
{
	/**
	* @Route("/backend/payment", name="user_backend_payment")
	*/
	public function indexAction(Request $request)
	{
		$userInfo = $this->get('app.userinfo');

		if($userInfo->hasIncompleteData()) {
			$this->addFlash(
	            'info',
	            'Bitte vervollständigen Sie Ihre Benutzerinformation, bevor sie ein Paket bestellen.'
	        );

	        return $this->redirectToRoute('fos_user_profile_edit');
		}

		return $this->renderPaymentForm($request);
	}

	public function renderPaymentForm($request)
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

        $form = $this->createFormBuilder($groupObj)
			->add('package', ChoiceType::class, [
			    'choices' 	=> array(
			    	'Basis' 	=> 'base', 
			    	'Premium' 	=> 'premium'
		    	),
			    'multiple' 	=> false,
			    'expanded' 	=> true,
		        'required' 	=> 'required',
		    ])
		    ->add('paymentType', ChoiceType::class, [
			    'choices' 	=> array(
			    	'Rechnung' 	=> 'invoice', 
			    	'Paypal' 	=> 'paypal'
		    	),
			    'multiple' 	=> false,
			    'expanded' 	=> true,
		        'required' 	=> 'required',
		    ])
		    ->add('xterms', CheckboxType::class, array(
			    'label'    		=> 'AGB',
			    'constraints'	=> new IsTrue(array('message'=>'Needs to be clicked')),
			    'mapped' 		=> false,
		        'required' 		=> 'required',
			))
            ->getForm();

        $isPayed = $groupObj->getIsPayed();

        // Do not set form delete if account is not payed
        if( $isPayed == 0 ) {
			$formDelete = false;
        }

        $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        $groupObj = $form->getData();
	        $groupObj->setIsPayed(true);

	        $em = $this->getDoctrine()->getManager();
	        $em->persist($groupObj);
	        $em->flush();

	        $this->addFlash(
	            'success',
	            'Herzlichen Glückwunsch zu dem Erwerb ihres AVINGA Accounts!'
	        );

	        return $this->redirectToRoute('user_backend_payment');
	    }

 		// Render delete abo form if account is payed
        if( $isPayed == 1 ) {

        	$formDelete = $this->createFormBuilder($groupObj)
				->getForm();

        	$formDelete->handleRequest($request);

        	if ($formDelete->isSubmitted()) {
        		$groupObj->setIsPayed(false);
        		$em = $this->getDoctrine()->getManager();
		        $em->persist($groupObj);
		        $em->flush();

		        $this->addFlash(
		            'success',
		            'Ihr AVINGA Account ist nun abbestellt. Sie können bis zum Ablauf Ihrer noch laufenden Bestellung weiterhin unsere Kurse benutzen.'
		        );

		        return $this->redirectToRoute('user_backend_payment');
        	}

        	$formDelete = $formDelete->createView();

        }

		return $this->render('user_backend/payment.html.twig', array(
			'formEdit' => $form->createView(),
			'formDelete' => $formDelete,
			'isPayed' => $isPayed,
		));
	}
}