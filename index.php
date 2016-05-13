<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './includes/database.php';
require './classes/user.class.php';

$app = new \Slim\App;

$app->get('/', function ($request, $response) {
    return $response->getBody()->write('Hello World');
});

$app->post('/users/register', function( Request $request, Response $response ) 
{

	$body = $this->request->getParsedBody();

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

	$user = new User( $first_name, $last_name, $email );

	$db = new Database();

	$data = $db->createUser( $user, $password );

	if( isset( $data['id'] ) )
	{
		return $response->withJson( $data, 200 );
	}
});

$app->run();