<?php
namespace AppBundle\UserBundle\EventListener; 

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CustomRegistrationRedirect implements EventSubscriberInterface
{
	private $router;

	public function __constuct(UrlGeneratorInterface  $router) 
	{
		$this->router = $router;
	}

	/**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
        	// Event to listen on and what method to call
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirm',
        );
    } 

    public function onRegistrationConfirm(FormEvent $event)
    {
        $url = $this->router->generate('homepage');

        $event->setResponse(new RedirectResponse($url));
    }
}