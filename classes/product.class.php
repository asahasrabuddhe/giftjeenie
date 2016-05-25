<?php

class Product
{
	private $_name;
	private $_images;
	private $_url;
	private $_description;
	private $_price;
	private $_currency;
	private $_category;
	private $_source;
	private $_meta;
	private $_trend_rating;

	public function __construct()
	{
		$_name  = '';
		$_images = array();
		$_description = '';
		$_price = 0;
		$_currency = 'CAD';
		$_trend_rating = '0';
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
		if( sizeof( $imagePaths ) > 0 )
		{
			return;
		}
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
		return $this->_price;
	}

	public function setUrl( $url )
	{
		$this->_url = $url;
	}

	public function getUrl()
	{
		return $this->_url;
	}

	public function setCurrency( $currency )
	{
		$this->_currency = $currency;
	}

	public function getCurrency()
	{
		return $this->_currency;
	}

	public function setSource( $source )
	{
		$this->_source = $source;
	}

	public function getSource()
	{
		return $this->_source;
	}

	public function setCategory( $category )
	{
		$this->_category = $category;
	}

	public function getCategory()
	{
		return $this->_category;
	}

	public function setMeta( $meta )
	{
		$this->_meta = $meta;
	}

	public function getMeta()
	{
		return $this->_meta;
	}

		public function setTrendRating( $trend_rating )
	{
		$this->_trend_rating = $_trend_rating;
	}

	public function getTrendRating()
	{
		return $this->_trend_rating;
	}
}