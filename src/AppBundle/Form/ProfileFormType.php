<?php 

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProfileFormType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array(
        		'required' => true,
        		'label' => 'Vorname*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('lastName', TextType::class, array(
        		'required' => true,
        		'label' => 'Nachname*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('companyName', TextType::class, array(
        		'required' => true,
        		'label' => 'Firma*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('street', TextType::class, array(
        		'required' => true,
        		'label' => 'Straße & Hausnummer*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('postcode', IntegerType::class, array(
        		'required' => true,
        		'label' => 'Postleitzahl*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('city', TextType::class, array(
        		'required' => true,
        		'label' => 'Stadt*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('country', TextType::class, array(
        		'required' => true,
        		'label' => 'Land*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('ustId', TextType::class, array(
        		'required' => true,
        		'label' => 'UstID*',
        		'attr' => array(
			          'class' => 'form-control'
			      )))
        	->add('gender', ChoiceType::class, array(
				    'choices'  => array(
				        'Männlich' => 'm',
				        'Weiblich' => 'w',
				    ),
				    'label' => 'Geschlecht',
				    'required' => false,
	        		'attr' => array(
				          'class' => 'form-control'
				      )))
        	->add('birthdate', DateType::class, array(
        		'widget' => 'choice',
        		'label' => 'Geburtstag',
        		'format' => 'dd-MM-yyyy',
        		'placeholder' => array(
        			'day' => 'Day',
        			'year' => 'Year', 
        			'month' => 'Month', 
    			),
        		'years' => range(1900,2016),
        		'days' => range(1,31),
        		'months' => range(1,12),
        		'required' => false));

    	$builder->remove('username');
    	$builder->remove('email');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}