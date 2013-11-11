<?php

class formRadio extends formField {
		
	var $output = [];
	
	public function add($name, $value, $attributes = []) {
		
		$attributes['type'] = 'radio';
		$attributes['value'] = $name;
		$attributes['name'] = $this->name;	
		
		if($attributes['value'] ==  $this->value)
			$attributes['checked'] = 'checked';
			
		$this->output[$attributes['value']] = ['value'=>$value, 'attr'=>$attributes]; //Name als Key speicher, für Methode del();	
		
		return $this;	
			
	}
	
	public function del($name) {
		
		unset($this->output[$name]);	
		
		return $this;	
			
	}
	
	public function get() {
		
		$return = '';
		foreach($this->output as $val) {
			$return .= '<label class="radio-inline"><input'.$this->convertAttr($val['attr']).'> '.$val['value'].'</label>';	
		}
		
		return $return;
		
	}
	
	
	
}


?>