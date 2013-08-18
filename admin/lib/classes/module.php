<?php

class module {
	
	const values = 15;
	const dyn_rex = 'DYN_(\w*)\[(\d)\]';
	static $types = array('VALUE', 'LINK', 'MEDIA', 'PHP'); #typen
	
	var $values = array();

	public static function getModuleList() {
	
		$return = array();
	
		$sql = new sql();
		
		$sql->result('SELECT id, name FROM module ORDER BY `sort`');
		while($sql->isNext()) {
		
			$return[] = array('id'=>$sql->get('id'), 'name' => $sql->get('name'));
		
			$sql->next();			
		}
		
		return $return;
		
	}
	
	public function convertValues($content) {
		
		preg_match_all('/'.self::dyn_rex.'/', $content, $values);
		$this->values = $values;
		
	}
	
	
	public function saveBlock($id) {
		
		$sql = new sql();
		$sql->setTable('structure_block');
		
		$sql->getPosts(array(
			'structure_id'=>'int',
			'sort'=>'int',
			'modul'=>'int'
		));
		
		foreach(self::$types as $types) {
			
			$array = type::post('DYN_'.$types, 'array', array());
			foreach($array as $key=>$value) {				
				$sql->addPost(strtolower($types).$key, $array[$key]);				
			}
						
		}
		
		if($sql->num('SELECT 1 FROM structure_block WHERE id='.$id)) {
			$sql->setWhere('id='.$id);
			$sql->update();	
		} else {
			$sql->save();	
		}
		
	}
	
	public function setFormBlock($form_id, $parent_id) {
		
		$form = new form('module', 'id='.$form_id, 'index.php');
		$form->setSave(false);
		$form->setMode('add');
		$form->addRawField($form->get('input'));
		$form->addHiddenField('parent_id', $parent_id);
		$form->addHiddenField('modul', $form->get('id'));
		$form->addHiddenField('sort', type::super('sort', 'int'));
		
		if($form->isSubmit()) {
				$this->saveBlock($form->get('id'));			
		}
		
		return $form;
		
	}
	
	public function setFormBlockout($form) {
		
		$return = '<div class="panel">';
		$return .= '	<div class="panel-heading">';
		$return .= '		<h3 class="panel-title pull-left">'.$form->get('name').'</h3>';
		$return .= '		<div class="clearfix"></div>';
		$return .= '	</div>';
		$return .= '	<div class="panel-body">';			
		$return .= 		$form->show();
		$return .= '	</div>';
		$return .= '</div>';
		
		return $return;
		
	}
	
	public function setSelectBlock($parent_id, $options, $sort = false) {
		
		$return  = '<div class="structure-addmodul-box">';
		$return .= '	<form action="index.php" method="get">';
		$return .= '		<input type="hidden" name="page" value="structure" />';
		$return .= '		<input type="hidden" name="subpage" value="content" />';
		$return .= '		<input type="hidden" name="parent_id" value="'.$parent_id.'" />';
		$return .= '		<input type="hidden" name="action" value="add" />';	
		
		if($sort)
			$return .= '		<input type="hidden" name="sort" value="'.$sort.'" />';
		
		$return .= '		<select name="modul" class="form-control">';
		$return .= '		<option>Modul hinzufügen</option>';
		$return .= $options;
		$return .= '		</select>';
		$return .= '	</form>';
		$return .= '</div>';
		
		return $return;
		
	}
	
	
	
}

?>