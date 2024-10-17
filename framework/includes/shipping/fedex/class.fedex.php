<?php

/**
 *	FileName: class.fedex.php
 *	 
 *	Description: Abstracts Fedex Shipping API getQuote function implementation
 *	
 *	
 * @author	
 *	 
 * @var account_number
 * @var meter
 * @var store_id
 * @var shipmethod_id
 * @var params	
 * 
 */


class Fedex extends FrameWork
{	
	var 	$store_id;
	var 	$shipmethod_id;
	var 	$account_number;
	var 	$meter;
	var 	$params;

	# Class Constructor
	function Fedex($store_id, $shipmethod_id, $params = array())
	{
		$this->store_id			=	$store_id;
		$this->shipmethod_id	=	$shipmethod_id;
		$this->params			=	$params;
		$this->FrameWork();
	}
	
	# This method calculates the shipping price
	function getFedexQuote()
	{
		
		;
		
	}
	
	
	
	


} # Close class definition

?>	