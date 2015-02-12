<?php

class Validate_Redirect extends Validate_Abstract
{
   
   protected $_error = "URL should be redirected ";
   protected $redirects = array('/link'=>'www.example.com/link-to-redirect');
  
   public function __construct($link){
 		parent::__construct($link);
   }
   
   public function isValid(){
   	 	if(isset($this->urlParts['path'])){   	 		
	   		foreach($this->redirects as $invalid => $valid){     				
	   			if($invalid == $this->urlParts['path']){      							
	   				$this->_error = "URL should be redirected from: ".$this->link->href." to: ".$valid;
	   				return false;
	   			}
	   		}
   	 	}
   		return true;	
   }

 	public function fix(){
   		return $this->redirects[$this->urlParts['path']];
    }
   
   
}
