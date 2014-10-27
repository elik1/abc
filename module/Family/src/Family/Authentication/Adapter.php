<?php
namespace Family\Authentication;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator;
use Family\Model\Entity\User as UserModel;

class Adapter extends AbstractAdapter implements ServiceLocatorAwareInterface
{
    
    protected $serviceLocator;

    public function authenticate()
    {
		$UsersTableGateway = $this->serviceLocator->get('table-gateway')->get('users', null, null);
		try {
			$rowset = $UsersTableGateway->select(array('userId' => $this->identity));
        }
		catch (\Exception $ex) {
            return;
        }
		$data = $rowset->current();
		if ($data) {
			$hydrator = new Hydrator\ClassMethods();
			$user = new UserModel();
			$hydrator->hydrate(get_object_vars($data), $user);
			if($user->verifyPassword($this->credential)) {
				return new Result(Result::SUCCESS, $user);
			}	
        }
	
        return new Result(Result::FAILURE, $this->identity);

    }
     
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

}
