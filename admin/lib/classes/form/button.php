<?php

class formButton extends formField {
	
	public function get() {		
		
		$this->addAttribute('name', $this->name);
		
		return '<button'.$this->convertAttr().'>'.$this->value.'</button>';
		
	}
	
}

?>