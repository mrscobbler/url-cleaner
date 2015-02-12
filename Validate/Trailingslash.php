<?php

class Validate_Trailingslash extends Validate_Abstract
{
   
   	protected $_error = "URL needs trailing slash";
   
   	public function isValid(){
		if(isset($this->urlParts['path'])){
   			
	  		 if(!isset($this->urlParts['fragment']) && !isset($this->urlParts['query']) && $this->urlParts['path'] != ''){	  		 	
	   			return substr($this->link->href,-1) == "/";	
	   		}else{
	   			return true;
	   		}
   		}else{
   			return true;
   		}
   		
   	}

 	public function fix(){
   		 return $this->link->href."/";
    }
   
}
