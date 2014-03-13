<?php

if($action == 'delete') {
	
	$sql = sql::factory();
	$sql->setTable('community_user');
	$sql->setWhere('id='.$id);
	$sql->delete();
	
}

if($action == 'edit'  || $action == 'add') {
	
	
	$form = form::factory('community_user', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('username', $form->get('username'));
	$field->fieldName('Username');
	
	$field = $form->addTextField('email', $form->get('email'));
	$field->fieldName('E-Mail');
	
	if(addonConfig::isActive('medienmanager')) {
		
		$field = $form->addTextField('avatar', $form->get('avatar'));
		$field->fieldName('Profilbild');
		
	}
	
	$field = $form->addCheckboxField('admin', $form->get('admin'));
	$field->add(1, 'Admin');
	
	$form->show();
	
}

if($action == '') {
	
	$table = table::factory();
	$table->setSql('SELECT * FROM '.sql::table('community_user'));
	while($table->isNext()) {
		
		$edit = '<a href="'.url::backend('community', ['subpage'=>'user', 'action'=>'edit', 'id'=>$table->get('id')].'" class="btn btn-sm btn-default fa fa-pencil-square-o></a>';
		$delete = '<a href="'.url::backend('community', ['subpage'=>'user', 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger fa fa-trash-o"></a>';
		
		$table->addRow()
		->addCell($table->get('id'))
		->addCell($table->get('username'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
	}
}

?>