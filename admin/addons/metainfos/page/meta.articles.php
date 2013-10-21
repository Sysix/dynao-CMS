<?php

$formTypes = array('text', 'textarea', 'select', 'radio', 'checkbox', 'DYN_LINK', 'DYN_MEDIA', 'DYN_LINK_LIST', 'DYN_MEDIA_LIST');

if($action == 'add' || $action == 'edit') {
	
	$form = new form('metainfo', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('label', $form->get('label'));
	$field->fieldName('Beschreibung');
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName('Name');
	
	$field = $form->addSelectField('type', $form->get('type'));
	$field->fieldName('Feldtyp');
	foreach($formTypes as $type) {
		$field->add($type, $type);	
	}
	
	$field = $form->addTextField('default', $form->get('default'));
	$field->fieldName('Standardwert');
	$field->addSuffix('<small>Bei Mehrauswahl mit einen <b>|</b> trennen</small>');
	
	$field = $form->addTextareaField('params', $form->get('params'));
	$field->fieldName('Parameter');
	
	$field = $form->addTextareaField('attributes', $form->get('attributes'));
	$field->fieldName('HTML-Attribute');
	$field->addSuffix('<small>Beispiel:<br /> style=color:red multiple=multiple class=my_css_class</small>');
	
	$form->addHiddenField('type', 'articles');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	echo $form->show();	
	
	
}

if($action == '') {
	
	echo '<a href="'.url::backend('meta', array('subpage'=>'articles', 'action'=>'add')).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
	
	$table = new table();
	$table->setSql('SELECT * FROM '.sql::table('metainfos').' WHERE `type` = "articles"');
	
	$table->addRow()->addCell('Name')->addCell('Aktion');
	
	$table->addSection('tbody');
	while($table->isNext()) {
		
		$edit = '<a href="'.url::backend('meta', array('subpage'=>'articles', 'action'=>'edit', 'id'=>$table->get('id'))).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
		$delete = '<a href="'.url::backend('meta', array('subpage'=>'articles', 'action'=>'delete', 'id'=>$table->get('id'))).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
		
		$table->addRow()
		->addCell($table->get('name'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
	
	echo $table->show();
	
}

?>