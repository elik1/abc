<?php
namespace Family\Controller;

use Family\Form\Transaction as TransactionForm;
use Zend\Mvc\Controller\AbstractActionController;
 
class TransactionController extends AbstractActionController 
{
	public function listAction() 
	{
		$id = $this->params('id', 0);
		if (!$id) {
			$currentUser = $this->serviceLocator->get('user');
			$role = $currentUser->getRole();
			if($role == 'admin') {
				return $this->redirect()->toRoute('family/list'); 
			}
			else {
				$id = $currentUser->getPassword();	
			}
		}
		$TransactionsTableGateway = $this->serviceLocator->get('table-gateway')->get('transactions', null, null);
        try {
			$resultSet = $TransactionsTableGateway->select(function($select) use ($id){
								$select->where(array('familyId' => $id));
								$select->order(array('date'));
							});
		}
		catch (\Exception $ex) {
            return $this->redirect()->toRoute('family/list');
        }
		 
		return array('transactions'=> $resultSet);
	}

	public function addAction()
    {
        $id = $this->params('id', 0);
		$form = new TransactionForm();
		$form->get('submit')->setAttribute('value', 'Add');
		$form->setData(array('familyId' => $id));
        if($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $form->setData($data);
            if($form->isValid()) {	
				$TransactionsTableGateway = $this->serviceLocator->get('table-gateway')->get('transactions', null, null);
				$TransactionsTableGateway->insert($form->getData());
				return $this->redirect()->toRoute('family/default', array('controller'=> 'transaction', 
																		  'action'=>'list', 
																		  'id'=> $id));  
            }
        }
        
        return array('form1'=> $form);
    }

}
