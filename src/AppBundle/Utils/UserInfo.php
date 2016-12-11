<?php

namespace AppBundle\Utils;

use FOS\UserBundle\Model\GroupManager;
use FOS\UserBundle\Model\GroupManagerInterface;
use FOS\UserBundle\Model\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Doctrine\ORM\EntityManager as ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserInfo
{
    protected $groupManager;
    protected $objectManager;
    protected $userManager;
    protected $context;

	public function __construct(GroupManager $groupManager, ObjectManager $objectManager, UserManager $userManager, TokenStorage $context)
	{
        $this->groupManager = $groupManager;
        $this->objectManager = $objectManager;
        $this->userManager = $userManager;	
        $this->context = $context;	
	}

	public function indexAction()
	{
		return;
	}

	public function hasIncompleteData() 
	{
		$user = $this->context->getToken()->getUser();
		// Check if required user data is incomplete
		$userData = array(
			$user->getFirstName(),
			$user->getLastName(),
			$user->getCompanyName(),
			$user->getUstId(),
			$user->getStreet(),
			$user->getPostcode(),
			$user->getCity(),
		);

		$missingData = false;

		foreach($userData as $data) {
			if($data == null) {
				$missingData = true;
			}
		}

		return $missingData;
	}

	public function hasPayed()
	{
		$hasPayed = false;
		$user = $this->context->getToken()->getUser();

		// Get group of user
		foreach($user->getGroups() as $group) {
			$hasPayed = $group->getIsPayed();
		}

		return $hasPayed;

	}
}