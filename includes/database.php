<?php

require dirname( __DIR__ ) . "/config.php";

class Database 
{
	private $connect;
	private $error_message;

	public function __construct() 
	{

		$this->error_message = '';

		$this->connect = new mysqli(HOST, USER, PASSWORD, DATABASE);

		if ($this->connect->connect_errno) 
		{
			$error_message = array("error" => "Failed to connect to MySQL: (" . $this->connect->connect_errno . ") " . $this->connect->connect_error);
		}
	}

	public function disconnect()
	{
		$this->connect->close();
		$this->connect = NULL;
	}

	public function createActivationToken( $user_id )
	{
		$activation = md5(uniqid(rand(), true));

		$stmt = $this->connect->prepare("INSERT INTO user_activation (user_id, token) VALUES (?, ?)");

		$stmt->bind_param( 'is', $user_id, $activation );
		$stmt->execute();

		$stmt->close();
	}

	public function generateToken( $user_id )
	{
		$api_key = hash('sha256', (time() . $user_id . md5(uniqid(rand(), true)) . rand()));
		$created_on = date('Y-m-d H:i:s');

		$stmt = $this->connect->prepare("INSERT INTO user_keys (user_id, token, created_on) VALUES (?, ?, ?)");

		$stmt->bind_param( 'iss', $user_id, $api_key, $created_on );
		$stmt->execute();

		echo $stmt->error;

		$stmt->close();

		return $api_key;
	}

	public function random_password( $length = 8 )
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    	$password = substr( str_shuffle( $chars ), 0, $length );
    	return $password;
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
			$source = $user->getSource();
			$source_id = $user->getSourceId();
			$profile_picture = $user->getProfilePicture();
			$status = $user->getStatus();
			$role = $user->getRole();
			$location = $user->getLocation();
			$created_on = date('Y-m-d H:i:s');
			$last_login_date = $created_on;
			$last_modified_on = $created_on;
			$last_logout = $created_on;

			$stmt->bind_param( 's', $email );
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows == 1)
			{
				$stmt->close();
				return array( 'message_code' => 104 );
			}

			$password = sha1( $email . ':' . $password );
			$password = password_hash($password, PASSWORD_BCRYPT);

			if( $insert_stmt = $this->connect->prepare( "INSERT INTO users ( first_name, last_name, email, password, source, source_id, status, role, profile_picture, location, created_on, last_login_date, last_modified_on, last_logout ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )" ) )
			{
				$insert_stmt->bind_param( 'ssssssiissssss', $first_name, $last_name, $email, $password, $source, $source_id, $status, $role, $profile_picture, $location, $created_on, $last_login_date, $last_modified_on, $last_logout );

				if( !$insert_stmt->execute() )
				{
					//$insert_stmt->close();
					return array('message_code' => 103 );
				}

				$insert_stmt->close();
				$user_id = $this->connect->insert_id;

				//$this->createActivationToken( $user_id );

				return array('message_code' => 154, 'id' => $user_id );
			}
			else
			{
				return array('message_code' => 103 );
			}
		}
	}

	public function verifyUser( $email, $password )
	{

		$stmt = $this->connect->prepare("SELECT id, password, status FROM users WHERE email = ? LIMIT 1");

		$stmt->bind_param( 's', $email );
		$stmt->execute();
		$stmt->bind_result( $user_id, $db_password, $status );
		$stmt->fetch();
		$stmt->close();
		$stmt = NULL;

		if( password_verify( sha1( $email . ':' . $password ), $db_password) )
		{
			if( 1 )//$status != 1 && $status == 2 )
			{
				$token = $this->generateToken( $user_id );
				$last_login_date = date('Y-m-d H:i:s');
				
				$stmt = $this->connect->prepare("UPDATE users SET last_login_date = ? WHERE id = ?");
				$stmt->bind_param( 'si', $last_login_date, $user_id );
				$stmt->execute();
				$stmt->close();
				$stmt = NULL;

				return array( 'message_code' => 153, 'id' => $user_id, 'token' => $token );
			}
			else
			{
				return array( 'message_code' => 100 );
			}
		}
		else
		{
			return array( 'message_code' => 105 );
		}
	}

	public function activateUser( $user_id, $key )
	{
		$stmt = $this->connect->prepare("SELECT token FROM user_activation WHERE user_id = ?");

		$stmt->bind_param( 'i', $user_id );
		$stmt->execute();
		$stmt->bind_result( $token );
		$stmt->fetch();
		$stmt->close();
		$stmt = NULL;

		if( $token == $key )
		{
			$stmt = $this->connect->prepare("UPDATE users SET status = 2 WHERE id = ?");

			$stmt->bind_param( 'i', $user_id );
			$stmt->execute();
			$stmt->close();

			return array( 'message_code' => 152 );
		}
		else
		{
			return array( 'message_code' => 102 );
		}
	}

	public function resetPassword( $email )
	{
		$stmt = $this->connect->prepare("SELECT id FROM users WHERE email = ?");

		$stmt->bind_param( 's', $email );
		$stmt->execute();
		$stmt->bind_result( $user_id );
		$stmt->fetch();
		$stmt->close();
		$stmt = NULL;

		if( isset( $user_id ) && !empty( $user_id ) )
		{
			$newpass = $this->random_password(8);

			$password = sha1( $email . ':' . $newpass );
			$password = password_hash($password, PASSWORD_BCRYPT);

			$stmt = $this->connect->prepare("UPDATE users SET password = ? WHERE id = ?");

			$stmt->bind_param( 'si', $password, $user_id );
			$stmt->execute();
			$stmt->close();
			$stmt = NULL;

			$to = $email;
			$subject = "Your GiftJeenie Password has been Reset";
         
			$message = "Your new password is " . $newpass;
         
			$header = "From:asahasrabuddhe@torinit.com \r\n";
			//$header .= "Cc:afgh@somedomain.com \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";

 			$result = mail( $to, $subject, $message, $header );

			return array( "message_code" => 151, "user_id" => $user_id );
		}

		return array( "message_code" => 101 );
	}

	public function logout( $user_id )
	{
		$last_logout = date('Y-m-d H:i:s');
		
		$stmt = $this->connect->prepare("UPDATE users SET last_logout = ? WHERE id = ?");
		$stmt->bind_param( 'si', $last_login_date, $user_id );
		$stmt->execute();
		$stmt->close();
		$stmt = NULL;

		$stmt = $this->connect->prepare("DELETE FROM user_keys WHERE user_id = ?");
		$stmt->bind_param( 'i', $user_id );
		$stmt->execute();
		$stmt->close();
		$stmt = NULL;

		return array( "message_code" => 150 );
	}

	public function addProduct( $product )
	{
		if( $this->error_message != '' )
		{
			return $error_message;
		}

		$name = $product->getName();
		$url = $product->getUrl();
		$price = $product->getPrice();
		$currency = $product->getCurrency();
		$trend_rating = $product->getTrendRating();
		$category_id = $product->getCategory();
		$image = $product->getImages();
		$source = $product->getSource();
		$description = $product->getDescription();
		$meta = $product->getMeta();

		return var_dump( $product );

		if( $insert_stmt = $this->connect->prepare("INSERT INTO product ( name, url, price, currency, trend_rating, category_id, image, source, description, created_on, created_by, last_modified_on, last_modified_by ) VALUES ( ?, ?, ?, ? )") )
		{
			$insert_stmt->bind_param( 'ssisiisssdidi', $name, $url, $price, $currency, $trend_rating, $category_id, $image, $source, $description);
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