<?php

$secondpage = type::super('secondpage', 'string');

if(!is_null($secondpage)) {
	
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
	
	if($action == 'add' || $action == 'edit') {
	
		$form = form::factory('slots', 'id='.$id, 'index.php');
		
		$field = $form->addTextField('name', $form->get('name'));
		$field->fieldName(lang::get('name'));
		$field->autofocus();
		
		$field = $form->addTextField('description', $form->get('description'));
		$field->fieldName(lang::get('description'));
		
		$field = $form->addRawField('<select name="modul" class="form-control">'.pageAreaHtml::moduleList($form->get('modul_id')).'</select>');
		$field->fieldName(lang::get('modul'));
		
		if($action == 'edit') {
			$form->addHiddenField('id', $id);	
		}
		
		if($form->isSubmit()) {
			
			$form->addPost('modul', $form->get('modul'));
				
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
		$table->setSql('SELECT * FROM '.sql::table('slots'));
		while($table->isNext()) {
			
			$name = '<a href="'.url::backend('structure', ['subpage'=>'slots', 'secondpage'=>'show', 'id'=>$table->get('id')]).'">'.$table->get('name').'</a>';
			$edit = '<a href='.url::backend('structure', ['subpage'=>'slots', 'action'=>'edit', 'id'=>$table->get('id')]).' class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
			$delete = '<a href='.url::backend('structure', ['subpage'=>'slots', 'action'=>'delete', 'id'=>$table->get('id')]).' class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
			
			$table->addRow()
			->addCell($name)
			->addCell($table->get('description'))
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();
		}
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title pull-left"><?php echo lang::get('slots_current_page'); ?></h3>
					<span class="btn-group pull-right">
						<a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'action'=>'add']); ?>" class="btn btn-default"><?php echo lang::get('add'); ?></a>
					</span>
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