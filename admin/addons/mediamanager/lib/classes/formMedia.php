<?php

class formMedia extends formField {
	
	public function get() {
		
		$this->addAttribute('disabled', 'disabled');
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addAttribute('type', 'text');
		$this->addAttribute('placeholder', 'Bild auswählen...');
		$this->addClass('form-control');		
		
		return '
		<div class="input-group dyn-media">
			<span class="input-group-addon"><i class="fa fa-bars fa-fw"></i></span>
			<input type="hidden" name="'.$this->name.'_id">
			<input '.$this->convertAttr().'>
		</div>';
		
	}
	
}

?>