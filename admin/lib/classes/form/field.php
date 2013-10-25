<?php

class formField {
	
	var $name;
	var $value;
	
	var $attributes = array();	
	
	var $suffix;
	var $prefix;
	var $fieldName;
	
	public function __construct($name, $value, $attributes = array()) {
		
		$this->name = $name;
		$this->value = $value;
		
		$this->attributes = $attributes;
		
	}
	
	public function setSuffix($suffix) {
		
		$this->suffix = $suffix;
			
	}
	
	public function setPrefix($prefix) {
	
		$this->prefix = $prefix;	
		
	}
	
	public function fieldName($name) {
		
		$this->fieldName = $name;	
		
	}
	
	public function addAttribute($name, $value) {
		
		if($name == 'class') {
			$this->addClass($value);
			return;	
		}
		
		$this->attributes[$name] = $value; 
		
	}
	
	public function hasAttribute($name) {
	
		return isset($this->attributes[$name]);	
		
	}
	
	public function getAttribute($name, $default = false) {
		
		if($this->hasAttribute($name)) {
			
			return $this->attributes[$name];
			
		}
		
		return $default;
		
	}
	
	public function delAttribute($name) {
			
		unset($this->attributes[$name]);
		
	}
	
	public function setReadonly($read) {
	
		if(!is_bool($read)) {
			//new Exception();				
		}
		
		if($read) {
			$this->addAttribute('readonly', 'readonly');	
		} else {
			$this->delAttribute('readonly');	
		}
		
	}
	
	protected function convertAttr($attr = false) {
		
		if(!$attr) {
		
			$attr = $this->attributes;
			
		}
		
		return html_convertAttribute($attr);		
		
	}
	
	public function addClass($class) {
	
		$this->attributes['class'][] = $class; 
		
	}
	
	public function autocomplete($auto) {
		
		if(!is_bool($auto)) {
			//new Exception();				
		}
		
		if($read) {
			$this->addAttribute('autocomplete', 'autocomplete');	
		} else {
			$this->delAttribute('autocomplete');	
		}
		
	}
	
	public function setID($id) {
	
		$this->addAttribute('id', $id);
		
	}
	
	public function getName() {
	
		return $this->name;
		
	}
	
	public function get() {}	
	
}

?>