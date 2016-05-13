<?php

class User
{
	private $_id;
	private $_first_name;
	private $_last_name;
	private $_email;
	private $_role;

	public function __construct( $first_name, $last_name, $email )
	{
		$this->_first_name	=	$first_name;
		$this->_last_name	=	$last_name;
		$this->_email		=	$email;
	}

	public function getFirstName()
	{
		return $this->_first_name;
	}

	public function getLastName()
	{
		return $this->_last_name;
	}

	public function getID()
	{
		return $this->_id;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function getRole()
	{
		return $this->role;
	}
}