<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require  dirname( __DIR__ ) . '/classes/user.class.php';

function giftj_user_register( Request $request, Response $response ) 
{

	$body = $request->getParsedBody();

	if( isset( $body['first_name'] ) )
	{
		$first_name = $body['first_name'];
	}
	else
	{
		$data = array( 'error' => 'First Name not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['last_name'] ) )
	{
		$last_name = $body['last_name'];
	}
	else
	{
		$data = array( 'error' => 'Last Name not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['email'] ) )
	{
		$email = $body['email'];
	}
	else
	{
		$data = array( 'error' => 'Email not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['password'] ) )
	{
		$password = $body['password'];
	}
	else
	{
		$data = array( 'error' => 'Password not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['source'] ) )
	{
		$source = $body['source'];
	}

	if( isset( $body['social_id'] ) )
	{
		$social_id = $body['social_id'];
	}
	else
	{
		$social_id = 0;
	}
	if( isset( $body['profile_picture'] ) )
	{
		$profile_picture = $body['profile_picture'];
	}
	else
	{
		$check = getimagesize( $_FILES["profile_picture"]["tmp_name"] );
		move_uploaded_file( $_FILES["profile_picture"]["tmp_name"], dirname( __DIR__ ) . '\uploads\profilepics\\' . $_FILES['profile_picture'] ['name']  );
		$profile_picture = dirname( __DIR__ ) . '\uploads\profilepics\\' . $_FILES['profile_picture'] ['name'];
	}

	$user = new User( $first_name, $last_name, $email, $source, $social_id, $profile_picture );

	$db = new Database();

	$data = $db->createUser( $user, $password );

	$db->disconnect();

	if( isset( $data['id'] ) )
	{
		return $response->withJson( $data, 200 );
	}
	else
	{
		return $response->withJson( $data, 409 );
	}
}

function giftj_user_login( Request $request, Response $response ) 
{
	$body = $request->getParsedBody();

	$email = $body['email'];
	$password = $body['password'];

	$db = new Database();
	$data = $db->verifyUser( $email, $password );
	$db->disconnect();

	return $response->withJson( $data, 200 );
}

function giftj_user_activate( Request $request, Response $response )
{
	$user_id = $request->getAttribute('id');
	$key = $request->getAttribute('key');

	$db = new Database();
	$data = $db->activateUser( $user_id, $key );
	$db->disconnect();

	return $response->withJson( $data, 200 );
}

function giftj_user_forgotpassword( Request $request, Response $response )
{
	$body = $request->getParsedBody();
	$email = $body['email'];

	$db = new Database();
	$data = $db->resetPassword( $email );
	$db->disconnect();

	return $response->withJson( $data, 200 );
}

function giftj_user_logout( Request $request, Response $response )
{
	$body = $request->getParsedBody();
	$user_id = $body['user_id'];

	$db = new Database();
	$data = $db->logout( $user_id );
	$db->disconnect();

	return $response->withJson( $data, 200 );
}