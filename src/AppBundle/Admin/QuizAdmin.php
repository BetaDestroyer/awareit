<?php 

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class QuizAdmin extends Admin 
{
    public function toString($object)
    {
        return $object instanceof CourseAdmin
            ? $object->getTitle()
            : 'Quiz'; // shown in the breadcrumb on the create view
    }

	// Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array(
                'label' => 'Name'
            ))
            ->add('question', 'sonata_type_collection', array(
                'type_options' => array(),
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
        ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('id')
           ->add('name')
       ;
    }

    public function prePersist($object)
    {
        if( !is_null($object->getQuestion()) ) {
            foreach ($object->getQuestion() as $question) {
                $question->setQuiz($object);
            }
        }
        
    }

    public function preUpdate($object)
    {
        if( !is_null($object->getQuestion()) ) {
            foreach ($object->getQuestion() as $question) {
                $question->setQuiz($object);
            }
        }
    }
}