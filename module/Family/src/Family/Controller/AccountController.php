<?php
namespace Family\Controller;

use Family\Form\Family as FamilyForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\ResultSet\ResultSet;
use Family\Model\Entity\Family as FamilyEntity;

class AccountController extends AbstractActionController 
{
	public function listAction() 
	{
		$FamiliesTableGateway = $this->serviceLocator->get('table-gateway')->get('families', null, null);
		$resultSet = $FamiliesTableGateway->select(function($select){
			$select->order(array('name'));
		});
		
		return array('families'=> $resultSet);
	}

	public function addAction()
    {
        $form = new FamilyForm();
		$form->get('submit')->setAttribute('value', 'Add');
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()) {
				$data = $form->getData();
			    $resultSetPrototype = new ResultSet();
				$FamilyEntity = new FamilyEntity();
                $resultSetPrototype->setArrayObjectPrototype($FamilyEntity);	
				$FamiliesTableGateway = $this->serviceLocator->get('table-gateway')->get('families', null, $resultSetPrototype);
				$FamilyEntity->exchangeArray($data);
				$FamiliesTableGateway->insert($data);
				
				$UsersTableGateway = $this->serviceLocator->get('table-gateway')->get('users', null, null);
				$UsersTableGateway->insert($data);
				 
				return $this->redirect()->toRoute('family/list');
            }
        }
        
        return array('form1'=> $form);
    }

	public function editAction() 
	{	
		$id = (int) $this->params('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('family/list');  
		}
		$FamiliesTableGateway = $this->serviceLocator->get('table-gateway')->get('families', null, null);
        try {
			$rowset = $FamiliesTableGateway->select(array('id' => $id));
        }
		catch (\Exception $ex) {
            return $this->redirect()->toRoute('family/list');
        }
		$family = $rowset->current();
         
        $form  = new FamilyForm();
        $form->bind($family);
        $form->get('submit')->setAttribute('value', 'Edit');
		if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
			unset($data['submit']);
			unset($data['csrf']);
            $form->setData($data);
            if($form->isValid()) {
			    $FamiliesTableGateway->update($data, array('id' => $id));
				return $this->redirect()->toRoute('family/list');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
	}
	public function meAction()
    {
        return array();
    }
	
	public function deniedAction()
    {
        return array();
    }
	
	public function deleteAction() 
	{
		return array();
	}
}
