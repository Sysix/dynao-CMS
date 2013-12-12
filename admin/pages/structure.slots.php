<?php

$secondpage = type::super('secondpage', 'string');

if(!is_null($secondpage) && dyn::get('user')->hasPerm('page[content]')) {
	
	if(!is_null(type::post('save-back')) || !is_null(type::post('save'))) {
		slot::saveBlock();
		echo message::success(lang::get('structure_content_save'), true);
	}
	
	if($secondpage == 'show') {
	
		$sql = sql::factory();
		$sql->query('
		SELECT 
		  s.*, m.output 
		FROM 
		  '.sql::table('slots').' AS s
		  LEFT JOIN
		  	'.sql::table('module').' AS m
				ON m.id = s.modul
		WHERE s.id='.$id)->result();
		$pageArea = new pageArea($sql);
		
		$form = form::factory('module', 'id='.$sql->get('modul'), 'index.php');
		$form->setSave(false);
		$form->addFormAttribute('class', '');
		$form->setSuccessMessage(null);
		
		$input = $pageArea->OutputFilter($form->get('input'), $sql);
		$form->addRawField($input);
		
		$form->addHiddenField('secondpage', $secondpage);
		$form->addHiddenField('id', $id);
		
	?>
	<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">"<?php echo $sql->get('name') ?>" <?php echo lang::get('edit'); ?></h3>
					</div>
					<div class="panel-body">
						<?php echo $form->show(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php
		
	}
	
} else {
	
	if($action == 'delete' && dyn::get('user')->hasPerm('page[delete]')) {
		
		$sql = sql::factory();
		$sql->setTable('slots');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		echo message::success(lang::get('slot_deleted'), true);
		
		$action = '';	
	}
	
	if($action == 'add' || $action == 'edit' && dyn::get('user')->hasPerm('page[edit]')) {
		
		layout::addJsCode("
	var button = $('#allcat-button');
	var content = $('#allcat-content');
	
	button.change(function() {
			if(button.is(':checked')) {
				content.stop().slideUp(300);
			} else {
				content.stop().slideDown(300);
			}
	});");
	
		$form = form::factory('slots', 'id='.$id, 'index.php');
		
		$field = $form->addTextField('name', $form->get('name'));
		$field->fieldName(lang::get('name'));
		$field->autofocus();
		
		$field = $form->addTextField('description', $form->get('description'));
		$field->fieldName(lang::get('description'));
		
		$field = $form->addRawField('<select name="modul" class="form-control">'.pageAreaHtml::moduleList($form->get('modul')).'</select>');
		$field->fieldName(lang::get('modul'));
		
		$field = $form->addCheckboxField('is-structure', $form->get('is-structure'));
		$field->fieldName(lang::get('slots_show'));
		$field->add('1', lang::get('all_categories'), ['id'=>'allcat-button']);
		
		$select = pageMisc::getTreeStructure(true, $form->get('structure'));
		
		if($form->get('is-structure') == 1)
			$select->addAttribute('style', 'display:none;');
		
		$select->setMultiple();
		$select->setSize(10);
		$select->setId('allcat-content');
		$form->addElement('pages', $select);
		
		if($action == 'edit') {
			$form->addHiddenField('id', $id);	
		}
		
		if($form->isSubmit()) {
			
			$form->addPost('modul', $form->get('modul'));
			$form->addPost('template', dyn::get('template'));
				
		}
		
	?>
		<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo lang::get('slots_add'); ?></h3>
				</div>
				<div class="panel-body">
					<?php echo $form->show(); ?>
				</div>
			</div>
		</div>
	</div>    
	<?php
	}
	
	if($action == '') {
	
		$table = table::factory();
		
		$table->addCollsLayout('200,*,110');
		
		$table->addRow()
		->addCell(lang::get('name'))
		->addCell(lang::get('description'))
		->addCell(lang::get('action'));
		
		$table->addSection('tbody');
		$table->setSql("SELECT * FROM ".sql::table('slots')." WHERE template = '".dyn::get('template')."'");
		if($table->numSql()) {
			while($table->isNext()) {
				
				$edit = '';
				$deleted = '';
				
				if(dyn::get('user')->hasPerm('page[content]')) {
					$name = '<a href="'.url::backend('structure', ['subpage'=>'slots', 'secondpage'=>'show', 'id'=>$table->get('id')]).'">'.$table->get('name').'</a>';
				} else {
					$name = $table->get('name');	
				}
				
				if(dyn::get('user')->hasPerm('page[edit]')) {
					$edit = '<a href='.url::backend('structure', ['subpage'=>'slots', 'action'=>'edit', 'id'=>$table->get('id')]).' class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
				}
				
				if(dyn::get('user')->hasPerm('page[delete]')) {
					$delete = '<a href='.url::backend('structure', ['subpage'=>'slots', 'action'=>'delete', 'id'=>$table->get('id')]).' class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
				}
				
				$table->addRow()
				->addCell($name)
				->addCell($table->get('description'))
				->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
				
				$table->next();
			}
		} else {
		
			$table->addRow()
			->addCell(lang::get('no_entries'), ['colspan'=>3]);
		
		}
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title pull-left"><?php echo lang::get('slots_current_page'); ?></h3>
                    <?php
					if(dyn::get('user')->hasPerm('page[edit]')) { 
					?>
					<span class="btn-group pull-right">
						<a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'action'=>'add']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
					</span>
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
	
}
?>