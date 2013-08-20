<?php

class module {
	
	const values = 15;
	const dyn_rex = 'DYN_(\w*)\[(\d)\]';
	const out_rex = 'OUT_(\w*)\[(\d)\]';
	static $types = array('VALUE', 'LINK', 'MEDIA', 'PHP'); #typen
	
	var $values = array();
	var $out = array();

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
	
	public function convertOut($content) {
		
		preg_match_all('/'.self::out_rex.'/', $content, $out);
		$this->out = $out;
		
	}
	
	public function OutputFilter($content, $sql) {
		
		if(!is_object($sql)) // SQL Muss Objekt sein
			return $content;
	
		$this->convertOut($content);
		
		foreach($this->out[1] as  $key=>$type) {
			
			if($key > self::values || !in_array($type, self::$types))
				continue;
				
			$value = strtolower($type).$this->out[2][$key];# value1
			
			// Wenn Formular Eintrag existiert und ID der SQL ID ist
			if(isset($_POST['DYN_'.$type][$this->out[2][$key]]) && $sql->get('id') == type::post('id', 'int', 0)) {
				
				$value = $_POST['DYN_'.$type][$this->out[2][$key]]; # $_POST['DYN_VALUE'][2]
				
			} else {
				
				$value = $sql->get($value);
				
			}
			$content = str_replace(
			$this->out[0][$key], # OUT_VALUE[1]
			$value,
			$content);
					
		}
		
		return $content;
		
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
		
		if($id) {
			$sql->setWhere('id='.$id);
			$sql->update();	
		} else {
			$sql->save();	
		}
		
	}
	
	public function delete($id) {
	
		$sql2 = new sql();		
		$sql2->query('SELECT `sort` FROM structure_block WHERE id='.$id)->result();
		
		$sql = new sql();
		$sql->setTable('structure_block');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		$this->saveSortUp($sql2->get('sort'), false);
		
	}
	
	protected function saveSortUp($sort, $plus = true) {
	
		$sql = new sql();
		$sql2 = new sql();
		$sql2->setTable('structure_block');
		$sql->query('SELECT `id`, `sort` FROM structure_block WHERE `sort` >= '.$sort)->result();
		while($sql->isNext()) {
			
			if($plus) {
				$sql2->addPost('sort', $sql->get('sort')+1);
			} else {
				$sql2->addPost('sort', $sql->get('sort')-1);
			}
			
			$sql2->setWhere('id='.$sql->get('id'));
			$sql2->update();
			
			$sql->next();
		}
		
	}
	
	public function setFormBlock($id, $form_id, $parent_id) {
		
		if($id) {
			$action = 'edit';
			$sql_id = $id;	
		} else {
			$action = 'add';
			$sql_id = 0;
		}
		
		$sql = new sql();
		$sql->query('SELECT * FROM structure_block WHERE id = '.$sql_id)->result();
		
		$form = new form('module', 'id='.$form_id, 'index.php');
		$form->setSave(false);
		$form->setMode($action);
		$input = $this->OutputFilter($form->get('input'), $sql);
		
		$form->addRawField($input);
		$form->addHiddenField('parent_id', $parent_id);
		$form->addHiddenField('modul', $form->get('id'));
		$form->addHiddenField('sort', type::super('sort', 'int'));
		$form->addHiddenField('id', $sql_id);
		
		if($form->isSubmit()) {
				
				$this->saveSortUp(type::super('sort', 'int'));
				$this->saveBlock($id);			
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