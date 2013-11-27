<?php

if(ajax::is()) {
	
	$sort = type::post('array', 'array');
	
	$sql = sql::factory();
	$sql->setTable('module');
	foreach($sort as $s=>$id) {
		$sql->setWhere('id='.$id);
		$sql->addPost('sort', $s+1);
		$sql->update();	
	}
	
	ajax::addReturn(message::success(lang::get('save_sorting'), true));
	
}

if($action == 'delete') {
	
	$sql = sql::factory();
	$sql->setTable('module');
	$sql->setWhere('id='.$id);
	$sql->delete();
	
	$action = '';
	
}

if($action == 'add' || $action == 'edit') {

	$form = form::factory('module', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName(lang::get('name'));
	
	$field = $form->addTextareaField('input', $form->get('input'));
	$field->fieldName(lang::get('input'));
	
	$field = $form->addTextareaField('output', $form->get('output'));
	$field->fieldName(lang::get('output'));
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
                <h3 class="panel-title pull-left"><?php echo ($action == 'add') ? lang::get('module_add') : '"'.$form->get('name').'" '.lang::get('edit'); ?></h3>
                <div class="btn-group pull-right">
                    <a class="btn btn-sm btn-default" href="<?php echo url::backend('structure', ['subpage'=>'module']); ?>"><?php echo lang::get('back'); ?></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
			<?php echo $form->show();?>
            </div>
		</div>
	</div>
</div>

<?php
	
}

if($action == '') {

	$table = table::factory(['class'=>['js-sort']]);
	
	$table->addCollsLayout('*,110');
	
	$table->addRow()
	->addCell('Name')
	->addCell('Aktion');
	
	$table->addSection('tbody');

	$table->setSql('SELECT * FROM '.sql::table('module').' ORDER BY sort ');
	
	if($table->numSql()) {
	
		while($table->isNext()) {
		
			$id = $table->get('id');
			
			$edit = '<a href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'edit', 'id'=>$id]).'" class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
			$delete = '<a href="'.url::backend('structure', ['subpage'=>'module','action'=>'delete', 'id'=>$id]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
			
			$table->addRow(['data-id'=>$id])
			->addCell($table->get('name'))
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
		}
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>2]);
		
	}

	?>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo lang::get('modules'); ?></h3>
                    <div class="btn-group pull-right">
                        <a class="btn btn-sm btn-default" href="<?php echo url::backend('structure', ['subpage'=>'module', 'action'=>'add']); ?>"><?php echo lang::get('add'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php echo $table->show(); ?>
            </div>
        </div>
    </div>

	<?php

}

?>