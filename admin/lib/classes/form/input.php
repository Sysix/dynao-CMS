<?php

class formInput extends formField {
	
	public function get() {		
		
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addClass('form-control');
		
		return '<input'.$this->convertAttr().' />';
		
	}
	
}

?>