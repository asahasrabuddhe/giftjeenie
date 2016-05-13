<?php

require "/../config.php";

class Database 
{
	private $connect;
	private $error_message;

	public function __construct() 
	{

		$error_message = '';

		$this->connect = new mysqli(HOST, USER, PASSWORD, DATABASE);

		if ($this->connect->connect_errno) 
		{
			$error_message = array("error" => 'Failed to connect to MySQL: (" . $this->connect->connect_errno . ") " . $this->connect->connect_error');
		}
	}

	public function getDatabaseInstance()
	{
		return $this->connect;
	}

	public function createUser( $user, $password )
	{
		if( $this->error_message != '' )
		{
			return $error_message;
		}

		$stmt = $this->connect->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");

		if( $stmt )
		{
			$first_name = $user->getFirstName();
			$last_name = $user->getLastName();
			$email = $user->getEmail();

			$stmt->bind_param( 's', $email );
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows == 1)
			{
				$stmt->close();
				return array('error' => 'User with entered e-mail already exists!');
			}

			$password = md5( $email . ':' . $password );
			$password = password_hash($password, PASSWORD_BCRYPT);

			if( $insert_stmt = $this->connect->prepare("INSERT INTO users ( first_name, last_name, email, password ) VALUES ( ?, ?, ?, ? )") )
			{
				$insert_stmt->bind_param( 'ssss', $first_name, $last_name, $email, $password);

				if( !$insert_stmt->execute() )
				{
					$insert_stmt->close();
					return array('error' => 'Registration Failure!');
				}

				$insert_stmt->close();
				return array('message' => 'Registration Sucessful', 'id' => $this->connect->insert_id );
			}
			else
			{
				return array('error' => 'Registration Failure!');
			}
		}
	}
}