<?php 

require __DIR__ . '/vendor/autoload.php';


class DaDataWrapper 
{
	private $dadata;
	
	function __construct($token, $secret)
	{
		$this->dadata = new \Dadata\DadataClient($token, $secret);
	}
}