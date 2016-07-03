<?php 

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class AnswerAdmin extends Admin 
{
	public function toString($object)
    {
        return $object instanceof CourseAdmin
            ? $object->getTitle()
            : 'Antworten'; // shown in the breadcrumb on the create view
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('answerText', 'text', array(
                'label' => 'Antworttext'
            ))
            ->add('isCorrect', 'choice', array(
                'label' => 'Ist richtige Antwort',
                'choices'  => array(
			        'Yes' => true,
			        'No' => false,
			    ),
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
            ->add('answerText')
            ->add('isCorrect')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('answerText')
            ->add('isCorrect')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('answerText')
           ->add('isCorrect')
       ;
    }
}