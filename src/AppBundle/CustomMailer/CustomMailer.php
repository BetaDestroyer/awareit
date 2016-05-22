<?php 

namespace AppBundle\CustomMailer;

use Symfony\Bundle\TwigBundle\TwigEngine;

class CustomMailer
{
	protected $mailer;
	protected $templating;
	protected $senderAdress;

	public function __construct(\Swift_Mailer $mailer, TwigEngine $templating, $senderAdress)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->senderAdress = $senderAdress;
	}

	public function sendCustomRegistrationMail($sendTo, $password) {
		$subject = "Willkommen bei AwareIT!";
		$body = $this->templating->render(
	                // app/Resources/views/Emails/registration.html.twig
	                'Emails/registration.html.twig',
	                array('password' => $password)
	            );

		$this->sendCustomMail($subject, $sendTo, $body);
	}

	public function sendCustomMail($subject, $sendTo, $body)
	{
	    $message = \Swift_Message::newInstance()
	            ->setSubject($subject)
	            ->setFrom($this->senderAdress)
	            ->setTo($sendTo)
		        ->setBody($body, 'text/html');

	    $this->mailer->send($message);
	}

}