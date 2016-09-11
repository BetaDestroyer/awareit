<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('AppBundle\Form\Type\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('homepage'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){
                    // Everything OK, redirect to wherever you want ! :  
                    $this->addFlash(
                        'notice',
                        'Ihre Anfrage wurde erfolgreich versendet. Wir werden Ihr Anliegen schnellstmöglich bearbeiten.'
                    );                  
                    return $this->redirectToRoute('homepage');
                }else{
                    // An error ocurred, handle
                    return $this->redirectToRoute('homepage');
                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'form' => $form->createView(),
        ]);
    }

    private function sendEmail($data){

        $mailer = $this->get('app.custom_mailer');

        $body = $this->render(
            // app/Resources/views/Emails/registration.html.twig
            'Emails/contact-response.html.twig',
            array(
                'name' => $data['name'],
                'email' => $data['email'],
                'company' => $data['company'],
                'subject' => $data['subject'],
                'message' => $data['message']
            )
        );

        $mailer->sendCustomMail($data['subject'], $this->container->getParameter( 'sender_adress' ), $body);

        return true;
        
    }

}
