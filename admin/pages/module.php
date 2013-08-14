<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

if($action == 'delete') {
	
	$sql = new sql();
	$sql->setTable('news');
	$sql->setWhere('id='.$id);
	$sql->delete();
	
	$action = '';
	
}

if($action == 'add' || $action == 'edit') {

	$form = new form('module', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName('Name');
	
	$field = $form->addTextareaField('input', $form->get('input'));
	$field->fieldName('Eingabe');
	
	$field = $form->addTextareaField('output', $form->get('output'));
	$field->fieldName('Ausgabe');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	echo $form->show();
	
}

if($action == '') {
	
	layout::addJsCode('
	var fixHelper = function(e, ui) {
        ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	}
$( "#sortable tbody" ).sortable({
	handle: ".icon-sort", 
	helper: fixHelper,
}).disableSelection();');
	
	echo '<a href="'.url::backend('module', array('action'=>'add')).'" class="btn btn-small btn-primary pull-right">'.lang::get('add').'</a>';
	echo '<div class="clearfix"></div>';

	$table = new table(array('id'=>'sortable'));
	
	$table->addCollsLayout('20,*,170');
	
	$table->addRow()
	->addCell()
	->addCell('Name')
	->addCell('Aktion');
	
	$table->addSection('tbody');

	$table->setSql('SELECT * FROM module ORDER BY sort ');
	while($table->isNext()) {
	
		$id = $table->get('id');
		
		$edit = '<a href="'.url::backend('module', array('action'=>'edit', 'id'=>$id)).'" class="btn btn-small btn-default">'.lang::get('edit').'</a>';
		$delete = '<a href="'.url::backend('module', array('action'=>'delete', 'id'=>$id)).'" class="btn btn-small btn-danger">'.lang::get('delete').'</a>';
		
		$table->addRow()
		->addCell('<i class="icon-sort"></i>')
		->addCell($table->get('name'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
	}

	echo $table->show();

}

?>