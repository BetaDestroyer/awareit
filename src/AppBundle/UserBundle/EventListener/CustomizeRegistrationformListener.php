<?php 
namespace AppBundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGenerator;
use FOS\UserBundle\Form\Type\RegistrationFormType;

/**
 * Listener responsible to add custom fields to registrationform
 */
class CustomizeRegistrationformListener implements  EventSubscriberInterface 
{
	public function __construct()
	{

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

    public static function generatePass(TokenGenerator $tokenGenerator)
    {
        $password = substr($tokenGenerator->generateToken(), 0, 8); // 8 chars
        return $password;
    }

    public function onCreateNewCustomer(FormEvent $event)
    {
    	// Generate Password
    	$password = $this->generatePass($object = new TokenGenerator);

    	// Fetch form data
    	$user = $event->getForm()->getData();

    	// Set generated Password 
    	$user->setPassword($password);
    	$user->setPlainPassword($password);
    }
}