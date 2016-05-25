<?php

class User
{
	private $_id;
	private $_first_name;
	private $_last_name;
	private $_email;
	private $_role;
	private $_source;
	private $_source_id;
	private $_profile_picture;
	private $_status;
	private $_location;

	public function __construct( $first_name, $last_name, $email, $source, $source_id, $profile_picture )
	{
		$this->_first_name	=	$first_name;
		$this->_last_name	=	$last_name;
		$this->_email		=	$email;
		$this->_source 		= 	$source;
		$this->_source_id 	= 	$source_id;
		$this->_profile_picture = $profile_picture;
		$this->_status = 1;
		$this->_role = 4;
		$this->_location = '';
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
		return $this->_role;
	}

	public function getSource()
	{
		return $this->_source;
	}

	public function getSourceId()
	{
		return $this->_source_id;
	}

	public function getProfilePicture()
	{
		return $this->_profile_picture;
	}

	public function getStatus()
	{
		return $this->_status;
	}

	public function getLocation()
	{
		return $this->_location;
	}
}