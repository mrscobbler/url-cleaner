<?php

abstract class Validate_Abstract
{
   	protected $_error = '';
	protected $link;
	protected $urlParts;
	
	public function __construct($link){
		$this->link = $link;
		$this->urlParts = parse_url($this->link->href);	
	}
	
   	public abstract function isValid();
	public abstract function fix();
	
   	public function getError(){
    	return $this->_error;
   	}
}
