<?php

class formCheckbox extends formField {
	
	var $output = array();
	
	public function add($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'checkbox';
		$attributes['value'] = $name;
		$attributes['name'] = $this->name;	
		
		if(strpos($this->value, '|'.$attributes['value'].'|') !== false)
			$attributes['checked'] = 'checked';
			
		$this->output[$attributes['value']] = '<input'.$this->convertAttr($attributes).'> '.$value; //Name als Key speicher, für Methode del();		
			
	}
	
	public function del($name) {
		
		if(isset($this->output[$name])) {			
			unset($this->output[$name]);				
		}
			
	}
	
	public function get() {
		
		$return = '';
		foreach($this->output as $val) {
			$return .= $val;	
		}
		
		return $return;
		
	}
	
	
	
}


?>