<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);


if($action == 'delete') {
	
		$sql = new sql();
		$sql->setTable('user');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		$action = '';
		
}

if($action == 'add' || $action == 'edit') {
	
	layout::addJsCode("
	var page_admin_button = $('#pageadmin-button');
	var page_admin_content = $('#pageadmin-content');
	
	page_admin_button.change(function() {
			if(page_admin_button.is(':checked')) {
				page_admin_content.stop().slideUp(300);
			} else {
				page_admin_content.stop().slideDown(300);
			}
	});");
	
	$form = new form('user','id='.$id,'index.php');
	
	$field = $form->addTextField('email', $form->get('email'));
	$field->fieldName('E-Mail Adresse');
	
	$field = $form->addTextField('password', $form->get('password'));
	$field->fieldName('Passwort');
	$field->setSuffix('<small>Sie sehen das verschlüsselte Passwort! Um ein neues Passwort zu vergeben, einfach eingeben!</small>');
	
	$field = $form->addCheckboxField('perms', $form->get('perms'));
	$field->add('admin[page]', 'PageAdmin?', array('id'=>'pageadmin-button'));
		
	$field = $form->addSelectField('perms', $form->get('perms'));
	$field->setMultiple(true);
	$field->setID('pageadmin-content');
	$field->setSize(8);
	$field->addGroup('Content');
	$field->add('content[edit]',	'Content bearbeiten / erstelle');
	$field->add('content[delete]',	'Content löschen');
	$field->addGroup('Medien');
	$field->add('media[edit]',		'Medien bearbeiten / erstellen');
	$field->add('media[delete]',	'Medien löschen');
	$field->addGroup('Admin');
	$field->add('admin[user]',		'Useradmin');
	$field->add('admin[addon]',		'AddOn Verwaltung');
	$field->add('admin[page]',		'Seiten Admin');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$post_password = type::post('password', 'string');
		
		if($post_password != $form->sql->get('password')) {
			
			$form->addPost('password', userLogin::hash($post_password));
			
		}

	}	
		
	
	echo $form->show();
	
	
}

if($action == '') {

	$table = new table();
	
	$table->addCollsLayout('*,170');
	
	$table->addRow()
	->addCell('E-Mail')
	->addCell('Aktion');
	
	$table->addSection('tbody');	
	
	$table->setSql('SELECT * FROM '.sql::table('user'));
	while($table->isNext()) {
			
		$id = $table->get('id');
			
		$edit = '<a href="'.url::backend('user', array('action'=>'edit', 'id'=>$id)).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
		$delete = '<a href="'.url::backend('user', array('action'=>'delete', 'id'=>$id)).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
		
		$table->addRow()
		->addCell($table->get('email'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
		
	echo $table->show();

	
}

?>