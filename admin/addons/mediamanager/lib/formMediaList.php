<?php

class formMediaList extends formField {
	
	public function __construct($name, $value) {
		
		parent::__construct($name, $value);
		$this->setSelected();
		$this->value = type::super($name, '', $this->value);
		
	}
	
	public function setSelected() {
		
		if(!is_array($this->value) && strpos($this->value, ',') !== false) {
			$this->value = explode(',', $this->value);
		}
				
		return $this;
		
	}	
	
	public function getSaveValue() {
		
		$name = $this->getName();
		$value = type::super($name);
		
		if(is_null($value)) {
			return '';	
		}
		
		return implode(',', $value);
		
	}
	
	protected function getOptions() {
		
		$return = [];
		
		if(empty($this->value)) {
			return '';
		}
		
		$sql = sql::factory();
		$sql->result('SELECT * FROM '.sql::table('media').' WHERE id IN ('.implode(',', (array)$this->value).')  ORDER BY FIND_IN_SET(id, "'.implode(',', (array)$this->value).'")');
		while($sql->isNext()) {
			
			$return[] = '<option value="'.$sql->get('id').'">'.$sql->get('filename').'</option>';
			
			$sql->next();
		}
		
		return implode(PHP_EOL, $return);
	}
	
	public function get() {
		
		$this->addAttribute('size', 8);
		$this->addAttribute('name', $this->name.'[]');
		$this->addAttribute('value', $this->value);
		$this->addAttribute('type', 'text');
		$this->addClass('form-control');
		
		return '
		<div class="input-group dyn-medialist">
			<select'.$this->convertAttr().'>
			'.$this->getOptions().'
			</select>
			<span class="input-group-addon">
				<i class="fa dyn-medialist-add" title="'.lang::get('select_media').'">=</i>
				<i class="fa fa-minus-square-o dyn-medialist-del" title="'.lang::get('element_delete').'"></i>
				<i class="fa fa-angle-double-up dyn-medialist-top" title="'.lang::get('element_to_top').'"></i>
				<i class="fa fa-angle-up dyn-medialist-up" title="'.lang::get('element_to_up').'"></i>
				<i class="fa fa-angle-down dyn-medialist-down" title="'.lang::get('element_to_down').'"></i>
				<i class="fa fa-angle-double-down dyn-medialist-bottom" title="'.lang::get('element_to_bottom').'"></i>		
			</span>
		</div>';
	}
	
}

?>