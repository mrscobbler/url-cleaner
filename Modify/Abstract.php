<?php

abstract class Modify_Abstract
{
   	protected $_error = '';
	protected $link;
	protected $urlParts;
	
	public function __construct($link){
		$this->link = $link;
		$this->urlParts = parse_url($this->link->href);	
	}
	
	/**
	 * @return bool - does the link require modification
	 */
   	public abstract function requiresModification();
	
	/**
	 * @return array of modified attributes
	 */
	public abstract function modify();
	
}
