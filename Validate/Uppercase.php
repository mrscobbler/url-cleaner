<?php

class Validate_Uppercase extends Validate_Abstract
{
   
    protected $_error = "URL contains uppercase letters";
	
   	public function isValid(){
   		if(!isset($this->urlParts['fragment']) && !isset($this->urlParts['query'])){
   			return strtolower($this->link->href) == $this->link->href;
   		}else{
   			return true;
   		}
   	}

 	public function fix(){
   		return strtolower($this->link->href);
    }
   
}
