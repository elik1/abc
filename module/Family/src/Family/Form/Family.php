<?php
namespace Family\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;

class Family extends Form
{
	public function __construct($name='family') {
		parent::__construct($name);
		
		$this->setAttribute('method', 'post');
 
        $this->add(array(
        		'name' => 'name',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'placeholder' => 'Type name...',
        				'required' => 'required',
        		),
        		'options' => array(
        				'label' => 'Name:',
        		),
        ));
		
		$this->add(array(
        		'name' => 'phone',
        		'options' => array(
        				'label' => 'Phone number:'
        		),
        		'attributes' => array(
        				'type' => 'tel',
        				'required' => 'required',
         				'pattern'  => '^[\d-/]+$'
        		),
        ));
		
		$this->add(array(
        		'name' => 'address',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'placeholder' => 'Type address...',
        				'required' => 'required',
        		),
        		'options' => array(
        				'label' => 'Address:',
        		),
        ));
		
        $this->add(array(
        		'name' => 'familyId',
				'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'required' => 'required',
        		),
        		'options' => array(
        				'label' => 'Family Id:',
        		),
        ));
        
        $this->add(array( 
            'name' => 'csrf', 
            'type' => 'Zend\Form\Element\Csrf', 
        ));       
        
        $this->add(array(
        		'name' => 'submit',
        		'type' => 'Zend\Form\Element\Submit',
        		'attributes' => array(
        				'value' => 'Submit',
        				'required' => 'false',
        		),
        ));
		
	}
	
	public function getInputFilter()
	{
		if (! $this->filter) {
			$inputFilter = new InputFilter();
			$factory = new InputFactory ();
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'name',
					'filters' => array (
							array (
									'name' => 'StripTags'
							),
							array (
									'name' => 'StringTrim'
							)
					),
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													'isEmpty' => 'Name is required'
											)
									)
							)
					)
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'phone',
					'filters' => array(
							array ( 'name' => 'digits' ),
							array ( 'name' => 'stringtrim' ),
					),
					'validators' => array (
							array (
									'name' => 'regex',
									'options' => array (
											'pattern' => '/^[\d-\/]+$/',
									)
							),
					)
			)));
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'address',
					'filters' => array (
							array (
									'name' => 'StripTags'
							),
							array (
									'name' => 'StringTrim'
							)
					),
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													'isEmpty' => 'Address is required'
											)
									)
							)
					)
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'familyId',
					'filters' => array (
							array (
									'name' => 'StripTags'
							),
							array (
									'name' => 'StringTrim'
							)
					),
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													'isEmpty' => 'Family Id is required!'
											)
									)
							)
					)
			) ) );
			
			$this->filter = $inputFilter;
		}
		
		return $this->filter;
	}
	
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception('It is not allowed to set the input filter');
	}
	
}