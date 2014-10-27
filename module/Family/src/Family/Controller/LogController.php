<?php
namespace Family\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManager;

class LogController extends AbstractActionController
{

    public function outAction()
    {
        $auth = $this->serviceLocator->get('auth');
        $auth->clearIdentity();
        return $this->redirect()->toRoute('home');
    }

    public function inAction()
    {
        if (!$this->getRequest()->isPost()) {
            
            return array();
        }

        $userId = $this->params()->fromPost('userId');
        $password = $this->params()->fromPost('password');

        $auth = $this->serviceLocator->get('auth');
        $authAdapter = $auth->getAdapter();
        $authAdapter->setIdentity($userId);
        $authAdapter->setCredential($password);

        $result = $auth->authenticate();
        $isValid = $result->isValid();
        if($isValid) {
            $user = $result->getIdentity();
            $this->flashmessenger()->addSuccessMessage('Welcome. You are now logged in.');

            return $this->redirect()->toRoute('family/default', array (
                    'controller' => 'account',
                    'action'     => 'me',
            ));
        } else {
			$this->flashmessenger()->addErrorMessage('You have entered an incorrect id or password.');
            return $this->redirect()->toRoute('family/default', array (
                    'controller' => 'log',
                    'action'     => 'in',
            ));
        }
    }
}
