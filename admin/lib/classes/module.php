<?php

class module {
	
	static $value = 15;
	static $link = 10;
	static $media = 10;
	static $php = 2;
	const dyn_rex = 'DYN_(\w*)\[(\d+)\]';
	const out_rex = 'OUT_(\w*)\[(\d+)\]';
	static $types = array('VALUE', 'LINK', 'MEDIA', 'PHP'); #typen
	
	var $values = array();
	var $out = array();

	public static function getModuleList() {
	
		$return = array();
	
		$sql = new sql();
		
		$sql->result('SELECT id, name FROM '.sql::table('module').' ORDER BY `sort`');
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
	
	public function convertOut($content) {
		
		preg_match_all('/'.self::out_rex.'/', $content, $out);
		$this->out = $out;
		
	}
	
	public function OutputFilter($content, $sql) {
		
		if(!is_object($sql)) // SQL Muss Objekt sein
			return $content;
	
		$this->convertOut($content);
		
		foreach($this->out[1] as  $key=>$type) {
			
			$maxValuesofType = strtolower($type);
			
			if($this->out[2][$key] > self::$$maxValuesofType || !in_array($type, self::$types))
				continue;
				
			$value = strtolower($type).$this->out[2][$key];# value1
			
			$content = str_replace(
			$this->out[0][$key], # OUT_VALUE[1]
			$sql->get($value),
			$content);
					
		}
		
		return $content;
		
	}	
	
	public static function saveBlock() {
		
		$id = type::post('id', 'int');
		
		$sql = new sql();
		$sql->setTable('structure_block');
		
		$sql->getPosts(array(
			'structure_id'=>'int',
			'sort'=>'int',
			'modul'=>'int',
			'online'=>'int'
		));
		
		foreach(self::$types as $types) {
			
			$array = type::post('DYN_'.$types, 'array', array());
			foreach($array as $key=>$value) {
				$sql->addPost(strtolower($types).$key, $value);			
			}
						
		}
		
		self::saveSortUp($sql->getPost('structure_id'), $sql->getPost('sort'));
		
		if($id) {
			$sql->setWhere('id='.$id);
			$sql->update();
		} else {
			$sql->save();
		}
		
	}
	
	public static function delete($id) {
	
		$sql = new sql();		
		$sql->query('SELECT `structure_id`, `sort` FROM '.sql::table('structure_block').' WHERE id='.$id)->result();
		
		$delete = new sql();
		$delete->setTable('structure_block');
		$delete->setWhere('id='.$id);
		$delete->delete();
		
		self::saveSortUp($sql->get('structure_id'), $sql->get('sort'), false);
		
		return $sql->get('structure_id');
		
	}
	
	protected static function saveSortUp($id, $sort, $up = true) {		
	
		sql::sortTable('structure_block', $sort, $up, '`structure_id` = '.$id);
		
	}
	
	public function setFormBlock($id, $form_id, $structure_id) {
		
		$action = ($id) ? 'edit' : 'add';
		
		$sql = new sql();
		$sql->query('SELECT * FROM '.sql::table('structure_block').' WHERE id = '.$id)->result();
		
		$form = new form('module', 'id='.$form_id, 'index.php');
		$form->setSave(false);
		$form->setMode($action);
		$input = $this->OutputFilter($form->get('input'), $sql);
		
		$form->addRawField($input);
		$form->addHiddenField('structure_id', $structure_id);
		$form->addHiddenField('modul', $form->get('id'));
		
		$online = ($id) ? $sql->get('online') : 1;
		$sort = ($id) ? $sql->get('sort') : type::super('sort', 'int');
		
		$field = $form->addRadioField('online', $online);
		$field->fieldName('Block Status');
		$field->add(1, 'Online');
		$field->add(0, 'Offline');
		
		$form->addHiddenField('sort', $sort);
		$form->addHiddenField('id', $id);

		return $form;
		
	}
	
	public function setFormBlockout($form) {
		
		$return = '<div class="panel panel-default">';
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
	
	public function setSelectBlock($structure_id, $options, $sort = false) {
		
		$return  = '<div class="structure-addmodul-box">';
		$return .= '	<form action="index.php" method="get">';
		$return .= '		<input type="hidden" name="page" value="structure" />';
		$return .= '		<input type="hidden" name="subpage" value="content" />';
		$return .= '		<input type="hidden" name="structure_id" value="'.$structure_id.'" />';
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