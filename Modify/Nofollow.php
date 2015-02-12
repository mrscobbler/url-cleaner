<?php

class Modify_Nofollow extends Modify_Abstract
{
   	public function requiresModification(){
   		return preg_match('/\/apply\/|debt.bills.com\/save|debtcoach.bills.com|loans.bills.com\/app\/start/', $this->link->href);
   	}
	
	public function modify(){
		return array('rel' => 'nofollow');
	}
}
