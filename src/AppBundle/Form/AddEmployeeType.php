<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class AddEmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, 
            	array(
            		'label' => 'E-Mail Adresse',
            		'attr' => array('class' => 'form-control'),
        		)
        	)
            ->add('save', SubmitType::class, 
            	array(
	            	'attr' => array('class' => 'btn btn-primary'),
	            	'translation_domain' => 'FOSUserBundle.de.yml',
	            	'label' => 'Mitarbeiter hinzufügen',
        		)
    		)
        ;
    }
}