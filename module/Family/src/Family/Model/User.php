<?php
namespace Family\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;

class User extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table = 'users';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }

    public function insert($set)
    {
        $set['userId'] = ($set['phone']);
		$set['password'] = $set['familyId'];
		$set['role'] = 'member';
		unset($set['name']);
		unset($set['phone']);
		unset($set['address']);
		unset($set['familyId']); 
		 
        return parent::insert($set);
    }
}
