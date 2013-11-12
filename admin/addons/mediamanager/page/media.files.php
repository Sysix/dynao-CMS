<?php

if($action == 'add' || $action == 'edit') {
	
	$form = form::factory('media', 'id='.$id, 'index.php');
	$form->addFormAttribute('enctype', 'multipart/form-data');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('Titel');
	
	$field = $form->addSelectField('category', $form->get('category'));
	$field->fieldName('Kategorie');
	
	$cat = sql::factory();
	$cat->query('SELECT * FROM '.sql::table('media_cat').' ORDER BY `sort`')->result();
	while($cat->isNext()) {
		$field->add($cat->get('id'), $cat->get('name').' ['.$cat->get('id').']');
		$cat->next();
	}
		
	$form = metainfos::getMetaInfos($form, 'media');
	
	$field = $form->addRawField('<input type="file" name="file" />');
	$field->fieldName('Datei auswählen');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$file = type::files('file');
		$form = mediaUtils::saveFile($file, $form);		
		
	}
	
	echo $form->show();	
	
}

if($action == '') {
	
	echo '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'add', 'id'=>$id]).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
	
	echo '<select class="form-control">';
	echo mediaUtils::getTreeStructure();
	echo '</select>';
	
	$table = table::factory();
	$table->setSql('SELECT * FROM '.sql::table('media'));
	$table->addRow()
	->addCell()
	->addCell('Titel')
	->addCell('Endung')
	->addCell('Aktion');
	$table->addSection('tbody');
	while($table->isNext()) {
			
		$media = new media($table->getSql());
		
		$edit = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'edit', 'id'=>$table->get('id')]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
		$delete = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
			
		$table->addRow()
		->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
		->addCell($media->get('title'))
		->addCell($media->getExtension())
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
	
	echo $table->show();
	
}

?>