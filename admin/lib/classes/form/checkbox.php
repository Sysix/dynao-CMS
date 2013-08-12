<?php

class formCheckbox extends formField {
	
	var $output = array();
	
	public function setChecked($checked) {
		
		if(!is_array($checked)) {
			$checked = explode('|', $checked);
		}
		
		$checked = array_flip($checked);
		
		$this->value = $checked;
		
	} 
	
	public function add($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'checkbox';
		$attributes['value'] = $name;
		$attributes['name'] = $this->name;	
		
		if(isset($this->value[$attributes['value']]))
			$attributes['checked'] = 'checked';
			
		$this->output[$attributes['value']] = '<input'.$this->convertAttr($attributes).'> '.$value; //Name als Key speicher, für Methode del();		
			
	}
	
	public function del($name) {	
		
			unset($this->output[$name]);
			
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