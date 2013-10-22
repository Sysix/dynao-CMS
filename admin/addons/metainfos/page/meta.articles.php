<?php

$formTypes = array('text', 'textarea', 'select', 'radio', 'checkbox', 'DYN_LINK', 'DYN_MEDIA', 'DYN_LINK_LIST', 'DYN_MEDIA_LIST');

if(ajax::is()) {
	
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

if($action == 'add' || $action == 'edit') {
	
	$form = new form('metainfos', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('label', $form->get('label'));
	$field->fieldName('Beschreibung');
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName('Name');
	
	$field = $form->addSelectField('formtype', $form->get('formtype'));
	$field->fieldName('Feldtyp');
	foreach($formTypes as $type) {
		$field->add($type, $type);	
	}
	
	$field = $form->addTextField('default', $form->get('default'));
	$field->fieldName('Standardwert');
	$field->setSuffix('<small>Bei Mehrauswahl mit einen <b>|</b> trennen</small>');
	
	$field = $form->addTextareaField('params', $form->get('params'));
	$field->fieldName('Parameter');
	
	$field = $form->addTextareaField('attributes', $form->get('attributes'));
	$field->fieldName('HTML-Attribute');
	$field->setSuffix('<small>Beispiel:<br /> style=color:red multiple=multiple class=my_css_class</small>');
	
	$form->addHiddenField('type', 'articles');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	echo $form->show();	
	
}

if($action == '') {
	
	echo '<a href="'.url::backend('meta', array('subpage'=>'articles', 'action'=>'add')).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
	
	$table = new table(array('class'=>array('js-sort')));
	$table->setSql('SELECT * FROM '.sql::table('metainfos').' WHERE `type` = "articles"');
	
	$table->addRow()->addCell()->addCell('Name')->addCell('Aktion');
	
	$table->addSection('tbody');
	while($table->isNext()) {
		
		$edit = '<a href="'.url::backend('meta', array('subpage'=>'articles', 'action'=>'edit', 'id'=>$table->get('id'))).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
		$delete = '<a href="'.url::backend('meta', array('subpage'=>'articles', 'action'=>'delete', 'id'=>$table->get('id'))).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
		
		$table->addRow(array('data-id'=>$table->get('id')))
		->addCell('<i class="icon-sort"></i>')
		->addCell($table->get('name'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
	
	echo $table->show();
	
}

?>