<?php 

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CourseAdmin extends Admin 
{

	public function toString($object)
    {
        return $object instanceof CourseAdmin
            ? $object->getTitle()
            : 'Kurse'; // shown in the breadcrumb on the create view
    }

	// Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
        	->with('Kurse')
        	->add('is_active', 'choice', array(
                'label' => 'Ist aktiv',
                'choices'  => array(
			        'Yes' => true,
			        'No' => false,
			    ),
            ))
            ->add('name', 'text', array(
                'label' => 'Name'
            ))
            ->add('description', 'text', array(
                'label' => 'Beschreibung'
            ))
            ->add('video', 'text', array(
                'label' => 'Video-link'
            ))
            ->add('file', 'file', array(
                'required' => false,
            ))
            ->add('thumbnail', 'text', array(
                'label' => 'Thumbnail'
            ))
            ->add('quiz', 'sonata_type_model', array(
                'class' => 'AppBundle\Entity\Quiz',
                'property' => 'name',
        	))
            ->end()
       ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
       $datagridMapper
			->add('isActive')
			->add('id')
			->add('name')
			->add('description')
			->add('video')
            ->add('thumbnail')
			->add('createdAt')
            ->add('updated')
       ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
			->add('isActive')
			->addIdentifier('id')
			->addIdentifier('name')
			->add('description')
			->add('video')
            ->add('thumbnail')
			->add('createdAt')
            ->add('updated')
       ;
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
			->add('isActive')
			->add('id')
			->add('name')
			->add('description')
			->add('video')
            ->add('thumbnail')
			->add('createdAt')
            ->add('updated')
       ;
    }

    public function prePersist($image)
    {
        $this->manageFileUpload($image);
    }

    public function preUpdate($image)
    {
        $this->manageFileUpload($image);
    }

    private function manageFileUpload($image)
    {
        if ($image->getFile()) {
            $image->refreshUpdated();
        }
    }
}