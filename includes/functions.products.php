<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require  dirname( __DIR__ ) . '/classes/product.class.php';


function giftj_add_product( Request $request, Response $response ) 
{

	$body = $request->getParsedBody();

	if( isset( $body['name'] ) )
	{
		$name = $body['name'];
	}
	else
	{
		$data = array( 'error' => 'Product Name not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['url'] ) )
	{
		$url = $body['url'];
	}
	else
	{
		$data = array( 'error' => 'Product URL not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['price'] ) )
	{
		$price = $body['price'];
	}
	else
	{
		$data = array( 'error' => 'Product Price not provided.' );
		return $response->withJson( $data, 409 );
	}

	if( isset( $body['image'] ) )
	{
		$image = $body['image'];
	}
	else if(  $check = getimagesize( $_FILES["image"]["tmp_name"] ) )
	{
		move_uploaded_file( $_FILES["image"]["tmp_name"], dirname( __DIR__ ) . '\uploads\\' . $_FILES['image'] ['name']  );
		$image[] = $_FILES["image"]["tmp_name"];
	}

	if( isset( $body['currency'] ) )
	{
		$currency = $body['currency'];
	}
	else
	{
		$currency = 'CAD';
	}

	if( isset( $body['category'] ) )
	{
		$category = $body['category'];
	}
	else
	{
		$category = '1';
	}

	if( isset( $body['source'] ) )
	{
		$source = $body['source'];
	}
	else
	{
		$source = '';
	}
	if( isset( $body['description'] ) )
	{
		$description = $body['description'];
	}
	else
	{
		$description = '';
	}
	

	$product = new Product();

	$product->setName( $name );
	$product->setUrl( $url );
	$product->setPrice( $price );
	$product->setImages( $image );
	$product->setCurrency( $currency );
	$product->setCategory( $category );
	$product->setSource( $source );
	$product->setDescription( $description );

	$meta['created_on'] = date(DATE_RFC2822);
	$meta['created_by'] = 4;
	$meta['last_modified_on'] = date(DATE_RFC2822);
	$meta['last_modified_by'] = 4;

	$product->setMeta( $meta );

	$db = new Database();

	$data = $db->addProduct( $product );

	/*if( isset( $data['id'] ) )
	{
		return $response->withJson( $data, 200 );
	}
	else
	{
		return $response->withJson( $data, 409 );
	}*/


	return $response->withJson( $product , 200 );
}