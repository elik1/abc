<?php
namespace Family\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;

class Transaction extends Form
{
	public function __construct($name='transaction') {
		parent::__construct($name);
		
		$this->setAttribute('method', 'post');
 
        $this->add(array(
        		'name' => 'type',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'placeholder' => 'type...',
        				'required' => 'required',
        		),
        		'options' => array(
        				'label' => 'Type:',
        		),
        ));
		
		$this->add(array(
        		'name' => 'date',
        		'type' => 'Zend\Form\Element\Date',
        		'attributes' => array(
        				'placeholder' => 'type date...',
        				'required' => 'required',
        		),
        		'options' => array(
        				'label' => 'Date:',
        		),
        )); 
		
		$this->add(array(
        		'name' => 'amount',
        		'type' => 'Zend\Form\Element\Text',
        		'attributes' => array(
        				'placeholder' => 'type amount...',
        				'required' => 'required',
        		),
        		'options' => array(
        				'label' => 'Amount:',
        		),
        ));
		 
        $this->add(array(
        		'name' => 'familyId',
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
					'name' => 'type',
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
													'isEmpty' => 'Type is required'
											)
									)
							)
					)
			) ) );
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'amount',
					'filters' => array(
							array ( 'name' => 'stringtrim' ),
					),
					'validators' => array (
							array (
									'name' => 'NotEmpty',
									'options' => array (
											'messages' => array (
													'isEmpty' => 'Amount is required'
											)
									)
							)
					)
			)));
			
			$inputFilter->add ( $factory->createInput ( array (
					'name' => 'date',
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
													'isEmpty' => 'Date is required!'
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