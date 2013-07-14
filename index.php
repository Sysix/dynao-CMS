<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('admin/lib/classes/sql.php');
require('admin/lib/classes/form.php');
require('admin/lib/classes/type.php');

require('admin/lib/classes/form/fields.php');
require('admin/lib/classes/form/text.php');
require('admin/lib/classes/form/textarea.php');
require('admin/lib/classes/form/radio.php');
require('admin/lib/classes/form/checkbox.php');
require('admin/lib/classes/form/select.php');
require('admin/lib/classes/form/button.php');
require('admin/lib/classes/form/raw.php');

sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

if($action == 'save' || $action == 'save-edit') {
	
	$sql = new sql();	
	$sql->setTable('news');
	$types = array('title'=>'string', 'text'=>'string');
					
	$sql->getPosts($types);
	$sql->addPost('date', time());
	
	
	if($action == 'save-edit') {
		$sql->setWhere('id='.$id);
		$sql->update();
	} else {
		$sql->save();	
	}
		
}

if($action == 'add' ||$action == 'edit') {

	$form = new form('news','id='.$id,'index.php');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('News-Titel');
	
	$field = $form->addTextareaField('text', $form->get('text'));
	$field->fieldName('Infotext');
	
	if($action == 'edit') {
		$form->addHiddenField('id', 1);
	}
	
	echo $form->show();
	
}

?>