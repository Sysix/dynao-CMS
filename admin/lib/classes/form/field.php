<?php

abstract class formField {
	use traitFactory;
	
	var $name;
	var $value;
	
	var $attributes;	
	
	var $suffix;
	var $prefix;
	var $fieldName;
	var $toSave = true;
	var $validator;
	
	public function __construct($name, $value, $attributes = []) {
		
		$this->name = $name;
		$this->value = $value;
		
		$this->attributes = $attributes;
		$this->validator = validator::factory();
		
	}
	
	public function setSuffix($suffix) {
		
		$this->suffix = $suffix;
		
		return $this;
			
	}
	
	public function setPrefix($prefix) {
	
		$this->prefix = $prefix;	
		
		return $this;
		
	}
	
	public function setSave($save) {
	
		$this->toSave = $save;
		
	}
	
	public function toSave() {
	
		return $this->toSave;
		
	}
	
	public function getSaveValue() {
		
		$name = $this->getName();
		$value = type::super($name);
		
		if(is_array($value)) {											
			$value = '|'.implode('|', $value).'|';					
		}
		
		return $value;
		
	}
	
	public function fieldName($name) {
		
		$this->fieldName = $name;	
		
		return $this;
		
	}
	
	public function addAttribute($name, $value = '') {
		
		if($name == 'class') {
			$this->addClass($value);
			return $this;	
		}
		
		$this->attributes[$name] = $value; 
		
		return $this;
		
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
		
		return $this;
		
	}
	
	public function addValidator($type, $message = null, $option = null) {
	
		$this->validator->add($type, $message, $option);
		
		return $this;
		
	}
	
	public function isValid() {
	
		return $this->validator->isValid($this->getSaveValue());
		
	}
	
	public function getError() {
	
		return $this->validator->getError();
		
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
		
		return $this;
		
	}
	
	protected function convertAttr($attr = false) {
		
		if(!$attr) {
		
			$attr = $this->attributes;
			
		}
		
		return html_convertAttribute($attr);		
		
	}
	
	public function addClass($class) {
	
		$this->attributes['class'][] = $class; 
		
		return $this;
		
	}
	
	public function autofocus($auto = true) {
		
		if(!is_bool($auto)) {
			//new Exception();				
		}
		
		if($auto) {
			$this->addAttribute('autofocus');	
		} else {
			$this->delAttribute('autofocus');	
		}
		
		return $this;
		
	}
	
	public function setId($id) {
	
		$this->addAttribute('id', $id);
		
		return $this;
		
	}
	
	public function getName() {
	
		return $this->name;
		
	}
	
	abstract public function get();
}

?>