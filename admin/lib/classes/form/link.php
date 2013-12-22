<?php

class formLink extends formField {
	
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
			$page = page::factory($this->value);
			$this->addAttribute('value', $page->get('name'));
		}
		
		$this->addAttribute('disabled', 'disabled');
		$this->addAttribute('name', $this->name);
		
		$this->addAttribute('type', 'text');
		$this->addAttribute('placeholder', lang::get('select_page').'...');
		$this->addClass('form-control');
		
		return '
		<div class="input-group dyn-link">
			<span class="input-group-addon"><i class="fa fa-bars dyn-link-add" title="'.lang::get('select_page').'"></i></span>
			<input type="hidden" name="'.$this->name.'_id" value="'.$this->value.'">
			<input'.$this->convertAttr().'>
			<span class="input-group-addon"><i class="fa fa-minus-square-o  dyn-link-del" title="'.lang::get('field_set_empty').'"></i></span>
		</div>';
		
	}
	
}

?>