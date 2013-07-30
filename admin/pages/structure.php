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

	$form = new form('news','id='.$id,'index.php');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('News-Titel');
	
	$field = $form->addTextareaField('text', $form->get('text'));
	$field->fieldName('Infotext');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$form->addPost('date', time());

	}	
	
	echo $form->show();
	
}
	
if($action == '') {

	$table = new table();
	
	$table->addRow()
	->addCell('Titel')
	->addCell('Datum')
	->addCell('Aktion');
	
	$table->addSection('tbody');
	
	$cacheFileName = cache::getFileName(1, 'news');
	
	if(cache::exist($cacheFileName)) {
		
		echo cache::read($cacheFileName);
		
	} else {
	
	
		$table->setSql('SELECT * FROM news ORDER BY date DESC');
		while($table->isNext()) {
			
			$id = $table->get('id');
			
			$edit = '<a href="index.php?action=edit&amp;id='.$id.'">'.lang::get('edit').'</a>';
			$delete = '<a href="index.php?action=delete&amp;id='.$id.'">'.lang::get('delete').'</a>';
			
			$table->addRow()
			->addCell($table->get('title'))
			->addCell(date('d.m.Y', $table->get('date')))	
			->addCell($edit.' | '.$delete);
			
			$table->next();	
		}
		
		$show = $table->show();
		
		cache::write($show, $cacheFileName);
		
		echo $show;
	
	}
	
}

?>