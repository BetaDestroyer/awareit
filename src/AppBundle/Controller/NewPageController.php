<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class NewPageController 
{
	/**
	* @Route("/newpage")
	*/
	public function sayHello()
	{
		return new Response("This is SYMFONY!!!11!!");
	}
}