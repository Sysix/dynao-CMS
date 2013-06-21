<?php

class formText extends formField {
	
	public function get() {
		
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addAttribute('type', 'text');
		
		return '<input'.$this->convertAttr().' />';
		
	}
	
}

?>