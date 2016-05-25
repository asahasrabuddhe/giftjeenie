<?php
require './vendor/autoload.php';
require './includes/database.php';
require './includes/functions.user.php';
require './includes/functions.products.php';

$app = new \Slim\App;

$app->get('/', function ($request, $response) {
    return $response->getBody()->write('Hello World');
});

$app->post( '/user/register', 'giftj_user_register' );

$app->post( '/user/login', 'giftj_user_login' );

$app->post( '/user/forgotpassword', 'giftj_user_forgotpassword');

$app->post( '/user/logout', 'giftj_user_logout' );

// $app->get( '/user/{id}/activate/{key}', 'giftj_user_activate' );

$app->get('/products', function( $request, $response) {
	
	$res = array( 'all', 'products' );
	return $response->withJson( $res, 200 );
});

$app->get('/products/id/{id}', function( $request, $response, $args ) {
	
	$id = $args['id'];

	$res = ['all', 'products'];
	
	if( is_numeric( $id ) ) {
		return $response->withJson( $res[$args['id']], 200 );
	}
	else {
		return $response->withJson( 'ID should be a numeric resouce !', 400);
	}
});

$app->get('/products/category/id/{id}', function( $request, $response, $args) {

	$id = $args['id'];

	$id = explode( ',', $id );

	return $response->withJson( $id, 200 );
});

$app->post('/products', 'giftj_add_product');

$app->run();