<?php 
namespace AppBundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGenerator;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use FOS\UserBundle\Mailer\MailerInterface;

/**
 * Listener responsible to add custom fields to registrationform
 */
class CustomizeRegistrationformListener implements  EventSubscriberInterface
{

	protected $mailer;
	protected $tokenGenerator;

	public function __construct(\Swift_Mailer $mailer, TokenGenerator $tokenGenerator)
	{
		$this->mailer = $mailer;
		$this->tokenGenerator = $tokenGenerator;
	}

	/**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
        	// Event to listen on and what method to call
            FOSUserEvents::REGISTRATION_SUCCESS => 'onCreateNewCustomer',
        );
    }

    public function onCreateNewCustomer(FormEvent $event)
    {

    	$password = $this->tokenGenerator->generateToken();
    	$password = substr($password, 0, 8);

    	// Fetch form data
    	$user = $event->getForm()->getData();

    	// Set generated Password 
    	$user->setPassword($password);
    	$user->setPlainPassword($password);

    	// Send Email with new generated Password
	    $message = \Swift_Message::newInstance()
	            ->setSubject("Betrefftest")
	            ->setFrom("service@awareit.com")
	            ->setTo($user->getEmail())
	            ->setBody("This is a test. And this is your password: ".$password."", 'text/html');

	    $this->mailer->send($message);

    }

}