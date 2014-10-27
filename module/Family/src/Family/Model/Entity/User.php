<?php
namespace Family\Model\Entity;

class User {
	protected $userId;
	protected $role;
	protected $password;

	public function getUserId() 
	{
		return $this->userId;
	}
 
	public function getRole() 
	{
		return $this->role;
	}

	public function setUserId($userId) 
	{
		$this->userId = $userId;
	}

	public function setRole($role) 
	{
		$this->role = $role;
	}

	public function getPassword()
	{
		return $this->password;
	}
	
	public function setPassword($password)
	{
		//$this->password = $this->hashPassword($password);
		$this->password = $password;
	}
	
	public function verifyPassword($password)
	{
		//return ($this->password == $this->hashPassword($password));
		return ($this->password == $password);
	}
	
	private function hashPassword($password)
	{
		return md5($password);
	}
}
