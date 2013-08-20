<?php

class formRadio extends formField {
		
	var $output = array();
	
	public function add($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'radio';
		$attributes['value'] = $name;
		$attributes['name'] = $this->name;	
		
		if($attributes['value'] ==  $this->value)
			$attributes['checked'] = 'checked';
			
		$this->output[$attributes['value']] = '<input'.$this->convertAttr($attributes).'> '.$value; //Name als Key speicher, für Methode del();		
			
	}
	
	public function del($name) {
		
		unset($this->output[$name]);		
			
	}
	
	public function get() {
		
		$return = '';
		foreach($this->output as $val) {
			$return .= '<label class="radio-inline">'.$val.'</label>';	
		}
		
		return $return;
		
	}
	
	
	
}


?>