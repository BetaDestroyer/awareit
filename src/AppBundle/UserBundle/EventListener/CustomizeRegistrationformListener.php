<?php 
namespace AppBundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\GroupManager;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Util\TokenGenerator;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use FOS\UserBundle\Mailer\MailerInterface;
use AppBundle\CustomMailer\CustomMailer;
use Doctrine\ORM\EntityManager as ObjectManager;

/**
 * Listener responsible to add custom fields to registrationform
 */
class CustomizeRegistrationformListener implements  EventSubscriberInterface
{

	protected $mailer;
	protected $tokenGenerator;
    protected $groupManager;
    protected $objectManager;

	public function __construct(CustomMailer $mailer, TokenGenerator $tokenGenerator, GroupManager $groupManager, ObjectManager $objectManager)
	{
		$this->mailer = $mailer;
		$this->tokenGenerator = $tokenGenerator;
        $this->groupManager = $groupManager;
        $this->objectManager = $objectManager;
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

        // Set role to manager
        $rolesArr = array('ROLE_MANAGER');
        $user = $event->getForm()->getData();
        $user->setRoles($rolesArr);

        // Create new groupobject
        $group = $this->groupManager->createGroup("fos_group_".$user->getEmail());

        // Save Groupobject
        $this->objectManager->persist($group);
        $this->objectManager->flush();

        // Send email with random generated password
    	$this->mailer->sendCustomRegistrationMail($user->getEmail(), $password);

    }

}