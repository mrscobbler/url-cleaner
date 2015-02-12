<?php

class Validate_Underscore extends Validate_Abstract
{
   
   	protected $_error = "URL has underscores ";
   
   
   	public function isValid(){
   		if(isset($this->urlParts['path'])){
   			return strpos($this->urlParts['path'],"_") === false;	
   		}else{
   			return true;
   		}
   	}

 	public function fix(){
 		$fixedPath = str_replace("_","-",$this->urlParts['path']);
 		if(isset($this->urlParts['path'])){
   			return str_replace($this->urlParts['path'],$fixedPath,$this->link->href); 	
 		}
    }
   
}
