<?php

class Product
{
	private $_name;
	private $_images;
	private $_description;
	private $_price;

	public function __construct()
	{
		$_name  = '';
		$_images = array();
		$_description = '';
		$_price = 0;
	}

	public function setName( $name )
	{
		$this->_name = $name;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setImage( $imagePath )
	{
		array_push( $this->_images, $imagePath );
	}

	public function setImages( $imagePaths )
	{
		foreach( $imagePaths as $imagePath )
		{
			$this->setImage( $imagePath );
		}
	}

	public function getImages()
	{
		return $this->_images;
	}

	public function setDescription( $description )
	{
		$this->_description = $description;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function setPrice( $price )
	{
		$this->_price = $price;
	}

	public function getPrice()
	{
		return $this->price;
	}
}