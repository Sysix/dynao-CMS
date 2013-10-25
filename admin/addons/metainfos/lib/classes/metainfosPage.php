<?php

class metainfosPage {
	
	static $types = array('text', 'textarea', 'select', 'radio', 'checkbox', 'DYN_LINK', 'DYN_MEDIA', 'DYN_LINK_LIST', 'DYN_MEDIA_LIST');
	
	static public function Backend($name, $pagename, $tablename, $action, $id) {
		
		if(ajax::is()) {
			self::BackendAjax();	
		}		
		
		if($action == 'add' || $action == 'edit' || $action == 'delete') {
			self::BackendFormular($name, $pagename, $tablename, $action, $id);
		}
		
		if($action == '') {
			self::BackendShow($name, $pagename);	
		}
		
		
	}
	
	static protected function BackendAjax() {
		
		$sort = type::post('array', 'array');
	
		$sql = new sql();
		$sql->setTable('metainfos');
		foreach($sort as $s=>$id) {
			$sql->setWhere('id='.$id);
			$sql->addPost('sort', $s+1);
			$sql->update();	
		}
		
		ajax::addReturn(message::success('Sortierung erfolgreich übernommen', true));
		
	}
	
	static protected function BackendFormular($name, $pagename, $tablename, $action, $id) {
		
		
		$form = new form('metainfos', 'id='.$id, 'index.php');
		
		if($action == 'delete') {
			self::delete($tablename, $id, $form->get('name'));
			$GLOBALS['action'] = '';
			return;
		}
	
		$field = $form->addTextField('label', $form->get('label'));
		$field->fieldName('Beschreibung');
		
		$field = $form->addTextField('name', $form->get('name'));
		$field->fieldName('Name');
		
		$field = $form->addSelectField('formtype', $form->get('formtype'));
		$field->fieldName('Feldtyp');
		$field->addAttribute('id', 'formtype');
		foreach(self::$types as $type) {
			$field->add($type, $type);	
		}
		
		$field = $form->addTextField('default', $form->get('default'));
		$field->fieldName('Standardwert');
		$field->setSuffix('<small>Bei Mehrauswahl mit einen <b>|</b> trennen</small>');
		
		$style = (in_array($form->get('formtype'), array('select', 'radio', 'checkbox'))) ? 'block' : 'none' ;
		
		$field = $form->addTextareaField('params', $form->get('params'));
		$field->fieldName('Parameter');
		$field->setPrefix('<div id="param_info" style="display:'.$style.'">');
		$field->setSuffix('Beispiele:<br />a) all|user|admin<br />b) 1:all|2:user|3:admin</div>');
		
		$field = $form->addTextareaField('attributes', $form->get('attributes'));
		$field->fieldName('HTML-Attribute');
		$field->setSuffix('<small>Beispiel:<br /> style=color:red<br />multiple=multiple<br />class=my_css_class</small>');
		
		$form->addHiddenField('type', $name);
		
		if($action == 'edit') {
			$form->addHiddenField('id', $id);
		}
		
		if($form->isSubmit()) {
		
			$sql = new sql();
			switch($form->get('formtype')) {
				case 'textarea':
					$type = 'text';
					break;
				default:
					$type = 'VARCHAR(255)';
					break;	
			}
			
			if($action == 'add') {
				$sql->query('ALTER TABLE '.$tablename.' ADD `'.$form->get('name').'` '.$type.' DEFAULT "'.$form->get('default').'" ');
			} else {
				$sql->query('ALTER TABLE '.$tablename.' CHANGE `'.$form->sql->getResult('name').'` `'.$form->get('name').'` '.$type.' DEFAULT "'.$form->get('default').'" ');
			}
			
		}
		
		echo $form->show();
		
		self::jsSelect();
		
	}
	
	static protected function BackendShow($name, $pagename) {
	
		echo '<a href="'.url::backend('meta', array('subpage'=>$pagename, 'action'=>'add')).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
	
		$table = new table(array('class'=>array('js-sort')));
		$table->setSql('SELECT * FROM '.sql::table('metainfos').' WHERE `type` = "'.$name.'"');
		
		$table->addRow()->addCell()->addCell('Name')->addCell('Aktion');
		
		$table->addCollsLayout('25,*,250');
		
		$table->addSection('tbody');
		while($table->isNext()) {
			
			$edit = '<a href="'.url::backend('meta', array('subpage'=>$pagename, 'action'=>'edit', 'id'=>$table->get('id'))).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
			$delete = '<a href="'.url::backend('meta', array('subpage'=>$pagename, 'action'=>'delete', 'id'=>$table->get('id'))).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
			
			$table->addRow(array('data-id'=>$table->get('id')))
			->addCell('<i class="icon-sort"></i>')
			->addCell($table->get('name'))
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
			
		}
		
		echo $table->show();
		
	}
	
	static protected function delete($tablename, $id, $name) {
		
		$sql = new sql();
		$sql->setTable('metainfos');
		$sql->setWhere('`id`='.$id);
		$sql->delete();
		
		$sql->query('ALTER TABLE '.$tablename.' DROP `'.$name.'`');
		
	}
	
	static protected function jsSelect() {
		
		layout::addJSCode('$("#formtype").change(function() {
			var value = $(this).val()  
			if(value == "radio" || value == "checkbox" || value == "select") {
				$("#param_info").slideDown();
			} else {
				$("#param_info").slideUp();
			}
			
		});');
			
	}
	
}

?>