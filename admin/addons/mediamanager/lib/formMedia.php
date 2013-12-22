<?php

class formMedia extends formField {
	
	public function __construct($name, $value) {
		
		parent::__construct($name, $value);
		$this->value = type::super($name.'_id', '', $this->value);
			
	}
	
	public function getSaveValue() {
		
		$name = $this->getName();
		$value = type::super($name.'_id');
				
		return $value;
		
	}
	
	public function get() {
		
		if($this->value != '') {
			$media = media::factory($this->value);
			$this->addAttribute('value', $media->get('filename'));
		}
		
		$this->addAttribute('disabled', 'disabled');
		$this->addAttribute('name', $this->name);
		
		$this->addAttribute('type', 'text');
		$this->addAttribute('placeholder', lang::get('select_media').'...');
		$this->addClass('form-control');
		
		return '
		<div class="input-group dyn-media">
			<span class="input-group-addon"><i class="fa fa-bars dyn-media-add" title="'.lang::get('select_media').'"></i></span>
			<input type="hidden" name="'.$this->name.'_id" value="'.$this->value.'">
			<input'.$this->convertAttr().'>
			<span class="input-group-addon"><i class="fa fa-minus-square-o  dyn-media-del" title="'.lang::get('field_set_empty').'"></i></span>
		</div>';
		
	}
	
}

?>