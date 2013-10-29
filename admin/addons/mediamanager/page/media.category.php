<?php

$parent_id = type::super('parent_id', 'int', 0);

$while_id = $id;

while($while_id) {
		
	$sql = new sql();
	$sql->query('SELECT name, parent_id FROM '.sql::table('media_cat').' WHERE id='.$while_id)->result();
	
	if($id != $while_id) {
		
		$breadcrumb[] = '<li><a href="'.url::backend('media_cat', array('id'=>$while_id, 'subpage'=>'category')).'">'.$sql->get('name').'</a></li>';
		
	} else {
		
		$breadcrumb[] = '<li class="active">'.$sql->get('name').'</li>';
		
	}
	
	$while_id = $sql->get('parent_id');
	
}

$breadcrumb[] = '<li><a href="'.url::backend('media', array('subpage'=>'category')).'">Homepage</a></li>';

echo '<ul class="pull-left breadcrumb">'.implode('', array_reverse($breadcrumb)).'</ul>';

echo '<a href="'.url::backend('media', array('subpage'=>'category', 'action'=>'add', 'id'=>$id)).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
echo '<div class="clearfix"></div>';

if($action == 'delete') {
	
	$sql = new sql();		
	$sql->query('SELECT `sort`, `parent_id` FROM '.sql::table('media_cat').' WHERE id='.$parent_id)->result();
	
	$delete = new sql();
	$delete->setTable('media_cat');
	$delete->setWhere('id='.$parent_id);
	$delete->delete();
	
	sql::sortTable('media_cat', $sql->get('sort'), false, '`parent_id` = '.$sql->get('parent_id'));
	
}

if(in_array($action, array('save-add', 'save-edit'))) {
	
	$sql = new sql();
	$sql->setTable('media_cat');
	$sql->setWhere('id='.$id);
	$sql->getPosts(array('name'=>'string','sort'=>'int', 'parent_id'=>'int'));
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		sql::sortTable('media_cat', type::post('sort', 'int'), true, '`parent_id` = '.type::post('parent_id', 'int'));
		$sql->save();	
	}
	
}

$table = new table(array('class'=>array('js-sort')));

$colFirstWidth = ($action == 'edit' || $action == 'add') ? 50 : 25; 

$table->addCollsLayout($colFirstWidth.',*,250');
$table->addRow()
->addCell()
->addCell('Name')
->addCell('Aktion');

$table->addSection('tbody');

$table->setSql('SELECT * FROM '.sql::table('media_cat').' WHERE parent_id = '.$id.' ORDER BY sort ASC');

if(in_array($action, array('edit', 'add'))) {
		
	echo '<form method="post" action="index.php">';
		
	$inputHidden = new formInput('action', 'save-'.$action);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = new formInput('page', 'media');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = new formInput('subpage', 'category');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = new formInput('parent_id', $id);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$buttonSubmit = new formButton('save', 'Artikel speichern');
	$buttonSubmit->addAttribute('type', 'submit');
	$buttonSubmit->addClass('btn');
	$buttonSubmit->addClass('btn-sm');
	$buttonSubmit->addClass('btn-default');
	
}

if($action == 'add') {
	
	$inputName = new formInput('name', '');
	$inputName->addAttribute('type', 'text');
	$inputName->addClass('form-control');
	$inputName->addClass('input-sm');
	
	$sql = new sql();
	$inputSort = new formInput('sort', $sql->num('SELECT 1 FROM '.sql::table('media_cat').' WHERE `parent_id`= '.type::super('id', 'int'))+1);
	$inputSort->addAttribute('type', 'text');
	$inputSort->addClass('form-control');
	$inputSort->addClass('input-sm');

	$table->addRow()
	->addCell($inputSort->get())
	->addCell($inputName->get())
	->addCell($buttonSubmit->get());	
	
}

while($table->isNext()) {
	
	if($action == 'edit' && $table->get('id') == $parent_id) {
			
		$inputName = new formInput('name', $table->get('name'));
		$inputName->addAttribute('type', 'text');
		$inputName->addClass('form-control');
		$inputName->addClass('input-sm');
		
		$inputSort = new formInput('sort', $table->get('sort'));
		$inputSort->addAttribute('type', 'text');
		$inputSort->addClass('form-control');
		$inputSort->addClass('input-sm');
		
		$inputHidden = new formInput('id', $table->get('id'));
		$inputHidden->addAttribute('type', 'hidden');
		
		$table->addRow()
		->addCell($inputSort->get())
		->addCell($inputName->get())
		->addCell($inputHidden->get().$buttonSubmit->get());
		
	} else {
		
		$edit = '<a href="'.url::backend('media', array('subpage'=>'category', 'action'=>'edit', 'parent_id'=>$table->get('id'))).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';	
		$delete = '<a href="'.url::backend('media', array('subpage'=>'category', 'action'=>'delete', 'parent_id'=>$table->get('id'))).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
			
		$table->addRow(array('data-id'=>$table->get('id')))
		->addCell('<i class="icon-sort"></i>')
		->addCell('<a href="'.url::backend('media', array('subpage'=>'category', 'id'=>$table->get('id'))).'">'.$table->get('name').'</a>')
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
	}
	
	$table->next();	
}

echo $table->show();


if(in_array($action, array('edit', 'add'))) {
	echo '</form>';
}

?>