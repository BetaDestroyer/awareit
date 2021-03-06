<?php 

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class QuestionAdmin extends Admin 
{
	public function toString($object)
    {
        return $object instanceof CourseAdmin
            ? $object->getTitle()
            : 'Fragen'; // shown in the breadcrumb on the create view
    }

	// Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('questionText', 'text', array(
                'label' => 'Textfrage'
            ))
            ->add('points', 'text', array(
                'label' => 'Punkte'
            ))
            ->add('answer', 'sonata_type_collection', array(
                'type_options' => array(),
                'label' => 'Antworten'
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
            ->add('questionText')
            ->add('points')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('questionText')
            ->add('points')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
           ->add('id')
           ->add('questionText')
           ->add('points')
       ;
    }

    public function prePersist($object)
    {
        if( !is_null($object->getAnswer()) ) {
            foreach ($object->getAnswer() as $answer) {
                $answer->setQuestion($object);
            }
        }
        
    }

    public function preUpdate($object)
    {
        if( !is_null($object->getAnswer()) ) {
            foreach ($object->getAnswer() as $answer) {
                $answer->setQuestion($object);
            }
        }
    }
}