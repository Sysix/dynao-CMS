<?php

class formLinklist extends formField {
	
	public function get() {
		
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		
	}
	
}

?>