<?php
namespace Family\Model\Entity;

class Family  
{
	public $id;
	public $name;
	public $phone;
	public $address;
	public $email;
	public $familyId;

	public function exchangeArray($data)
	{
		$this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
	    $this->phone  = (isset($data['phone'])) ? $data['phone'] : null;
		$this->address  = (isset($data['address'])) ? $data['address'] : null;
		$this->email  = (isset($data['email'])) ? $data['email'] : null;
		$this->familyId  = (isset($data['familyId'])) ? $data['familyId'] : null;
	}

	public function getArrayCopy()
    {
		return get_object_vars($this);
	}
}