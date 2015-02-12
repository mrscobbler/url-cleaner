<?php

class Validate_Https extends Validate_Abstract
{
  
   protected $_error = "URL has incorrect http (http:// or https://)";
   protected $validHttpsSubdomains = array('loans.bills.com','debtcoach.bills.com','debt.bills.com');
   
   public function isValid(){
   		$httpsSubdomain = isset($this->urlParts['host']) && in_array($this->urlParts['host'], $this->validHttpsSubdomains);
   		$hasApply 		= isset($this->urlParts['path']) && strpos($this->urlParts['path'],'apply/')  !== false;
   		
   		if(isset($this->urlParts['scheme'])){
	   		if($this->urlParts['scheme'] == 'https' && $hasApply === false && !$httpsSubdomain){
	   			return false;
	   		}else{
	   			if($this->urlParts['scheme']== 'http' && $httpsSubdomain){
	   				return false;
	   			}else{
	   				return true;
	   			}
	   		}
	   	}else{
	   		return $hasApply === false;
	   	}
   		
   }	
   
   public function fix(){
   		$httpsSubdomain = isset($this->urlParts['host']) && in_array($this->urlParts['host'], $this->validHttpsSubdomains);
   		$hasApply 	 	= isset($this->urlParts['path']) && strpos($this->urlParts['path'],'apply/')  !== false;
   		
  		if(isset($this->urlParts['scheme'])){
   			if($this->urlParts['scheme'] == 'https' && $hasApply === false && !$httpsSubdomain){
   				return str_replace('https','http',$this->link->href);
   			}else if($httpsSubdomain){
   				return str_replace('http','https',$this->link->href);
   			}
   		}else{
   			if($hasApply){
   				return "https://www.bills.com".$this->link->href;
   			}   			
   		}   		
   }
  
}
