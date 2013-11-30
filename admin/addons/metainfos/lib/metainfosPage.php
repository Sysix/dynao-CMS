<?php

class metainfosPage {
	
	static $types = ['text', 'textarea', 'select', 'radio', 'checkbox', 'DYN_LINK', 'DYN_LINK_LIST'];
	
	static public function addType($name) {
		
		self::$types[] = $name;	
		
	}
	
	static public function Backend($name, $pagename, $tablename, $action, $id) {
		
		if(
			dyn::get('user')->hasPerm('metainfos[edit]') ||
			dyn::get('user')->hasPerm('metainfos[delete]')
		) {
			
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
		
		
	}
	
	static protected function BackendAjax() {
		
		$sort = type::post('array', 'array');
	
		$sql = sql::factory();
		$sql->setTable('metainfos');
		foreach($sort as $s=>$id) {
			$sql->setWhere('id='.$id);
			$sql->addPost('sort', $s+1);
			$sql->update();	
		}
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
	}
	
	static protected function BackendFormular($name, $pagename, $tablename, $action, $id) {
		
		$prefix = substr($tablename, 0, 3).'_';
		
		$form = form::factory('metainfos', 'id='.$id, 'index.php');
		
		if($action == 'delete' && dyn::get('user')->hasPerm('metainfos[delete]')) {
			self::delete($tablename, $id, $form->get('name'));
			$form->setSuccessMessage(lang::get('entry_deleted'));
			$form->redirect();
			return;
		}
		
		$field = $form->addRawField($prefix);
		$field->fieldName(lang::get('description'));
	
		$field = $form->addTextField('label', $form->get('label'));
		$field->fieldName(lang::get('description'));
		$field->autofocus();
		
		if($form->isSubmit()) {
			$name = $form->get('name');	
		} else {
			$name = substr($form->get('name'), 4);
		}
		
		$field = $form->addTextField('name', $name);
		$field->fieldName(lang::get('name'));
		
		$field = $form->addSelectField('formtype', $form->get('formtype'));
		$field->fieldName(lang::get('field_type'));
		$field->addAttribute('id', 'formtype');
		foreach(self::$types as $type) {
			$field->add($type, $type);	
		}
		
		$field = $form->addTextField('default', $form->get('default'));
		$field->fieldName(lang::get('default_value'));
		$field->setSuffix(lang::get('meta_pre_selection'));
		
		$style = (in_array($form->get('formtype'), ['select', 'radio', 'checkbox'])) ? 'block' : 'none' ;
		
		$field = $form->addTextareaField('params', $form->get('params'));
		$field->fieldName(lang::get('meta_parameter'));
		$field->setPrefix('<div id="param_info" style="display:'.$style.'">');
		$field->setSuffix(lang::get('examples').':<br />a) all|user|admin<br />b) 1:all|2:user|3:admin</div>');
		
		$field = $form->addTextareaField('attributes', $form->get('attributes'));
		$field->fieldName('HTML-Attribute');
		$field->setSuffix('<small>Beispiel:<br /> style=color:red<br />multiple=multiple<br />class=my_css_class</small>');
		
		$form->addHiddenField('type', $name);
		
		if($action == 'edit') {
			$form->addHiddenField('id', $id);
		}
		
		if($form->isSubmit()) {
		
			$sql = sql::factory();
			switch($form->get('formtype')) {
				case 'textarea':
					$type = 'text';
					break;
				default:
					$type = 'VARCHAR(255)';
					break;	
			}
			
			$form->addPost('name', $prefix.$form->get('name'));
			
			$colum = sql::showColums($tablename, $prefix.$form->get('name'), false);
			$colum->result();
			// Wenn beim Hinzufügen schon vorhanden
			// Oder bei Editieren vorhanden, jedoch der nicht das 
			if($colum->num() && $action == 'add' || $action == 'edit' && $colum->num() && $form->sql->getResult('name') != $prefix.$form->get('name')) {
				
				$form->setErrorMessage(sprintf(lang::get('col_name_exists'), $prefix.$form->get('name')));
				$form->setSave(false);

			} else {
				
				if($action == 'add') {
					$sql->query('ALTER TABLE '.$tablename.' ADD `'.$prefix.$form->get('name').'` '.$type.' DEFAULT "'.$form->get('default').'" ');
				} else {
					$sql->query('ALTER TABLE '.$tablename.' CHANGE `'.$form->sql->getResult('name').'` `'.$prefix.$form->get('name').'` '.$type.' DEFAULT "'.$form->get('default').'" ');
				} 
				
			}
			
		}
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang::get('metainfo_edit'); ?></h3>
			</div>
			<div class="panel-body">
				<?php echo $form->show(); ?>
			</div>
		</div>
	</div>
</div>
<?php
		
		self::jsSelect();
		
	}
	
	static protected function BackendShow($name, $pagename) {
	
		$table = table::factory(['class'=>['js-sort']]);
		$table->setSql('SELECT * FROM '.sql::table('metainfos').' WHERE `type` = "'.$name.'"');
		
		$table->addRow()->addCell()->addCell('Name')->addCell('Aktion');
		
		$table->addCollsLayout('25,*,250');
		
		$table->addSection('tbody');
		while($table->isNext()) {
			
			$edit = '';
			$delete = '';
			
			if(dyn::get('user')->hasPerm('metainfos[edit]')) {
				$edit = '<a href="'.url::backend('meta', ['subpage'=>$pagename, 'action'=>'edit', 'id'=>$table->get('id')]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
			}
			
			if(dyn::get('user')->hasPerm('metainfos[delete]')) {
				$delete = '<a href="'.url::backend('meta', ['subpage'=>$pagename, 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger delete">'.lang::get('delete').'</a>';
			}
			
			$table->addRow(['data-id'=>$table->get('id')])
			->addCell('<i class="fa fa-sort"></i>')
			->addCell($table->get('name'))
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
			
		}
		?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo backend::getCurrentSubpageName(); ?></h3>
                <?php
				if(dyn::get('user')->hasPerm('metainfos[edit]')) {
				?>
				<div class="btn-group pull-right">
					<a href="<?php echo url::backend('meta', ['subpage'=>$pagename, 'action'=>'add']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
				</div>
                <?php
				}
				?>
				<div class="clearfix"></div>
			</div>
			<?php echo $table->show(); ?>
		</div>
	</div>
</div>
		<?php
		
	}
	
	static protected function delete($tablename, $id, $name) {
		
		$sql = sql::factory();
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