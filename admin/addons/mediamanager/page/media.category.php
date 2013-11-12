<?php

$pid = type::super('pid', 'int', 0);

$while_id = $pid;

while($while_id) {
		
	$sql = sql::factory();
	$sql->query('SELECT name, pid FROM '.sql::table('media_cat').' WHERE id='.$while_id)->result();
	
	if($pid != $while_id) {
		
		$breadcrumb[] = '<li><a href="'.url::backend('media', ['pid'=>$while_id, 'subpage'=>'category']).'">'.$sql->get('name').'</a></li>';
		
	} else {
		
		$breadcrumb[] = '<li class="active">'.$sql->get('name').'</li>';
		
	}
	
	$while_id = $sql->get('pid');
	
}

$breadcrumb[] = '<li><a href="'.url::backend('media', ['subpage'=>'category']).'">Homepage</a></li>';

echo '<ul class="pull-left breadcrumb">'.implode('', array_reverse($breadcrumb)).'</ul>';

echo '<a href="'.url::backend('media', ['subpage'=>'category', 'action'=>'add', 'pid'=>$pid]).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
echo '<div class="clearfix"></div>';

if($action == 'delete') {
	
	$orginal_id = $id;
	
	while($id) {
	
		$sql = sql::factory();
		$sql->query('SELECT id FROM '.sql::table('media_cat').' WHERE `pid` = '.$id)->result();
		if($sql->num()) {
			
			$id = $sql->get('id');
			
			$delete = sql::factory();
			$delete->setTable('media_cat');
			$delete->setWhere('id='.$id);
			$delete->delete();			
			
		} else {
			$id = false;	
		}
		
	}
	
	
	$sql = sql::factory();		
	$sql->query('SELECT `sort`, `pid` FROM '.sql::table('media_cat').' WHERE id='.$orginal_id)->result();
	
	$delete = sql::factory();
	$delete->setTable('media_cat');
	$delete->setWhere('id='.$orginal_id);
	$delete->delete();
	
	sql::sortTable('media_cat', $sql->get('sort'), false, '`pid` = '.$sql->get('pid'));
	
	echo message::success('Artikel erfolgreich gelöscht');
		
}

if(in_array($action, ['save-add', 'save-edit'])) {
	
	$sql = sql::factory();
	$sql->setTable('media_cat');
	$sql->setWhere('id='.$id);
	$sql->getPosts([
		'name'=>'string',
		'sort'=>'int',
		'pid'=>'int'
	]);
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		$sql->save();
		sql::sortTable('media_cat', type::post('sort', 'int'), true, '`pid` = '.type::post('pid', 'int'));	
	}
	
	
}

$table = table::factory(['class'=>['js-sort']]);

$colFirstWidth = ($action == 'edit' || $action == 'add') ? 50 : 25; 

$table->addCollsLayout($colFirstWidth.',*,250');
	
$table->addRow()
->addCell()
->addCell('Artikel')
->addCell('Aktion');

$table->addSection('tbody');
	
$table->setSql('SELECT * FROM '.sql::table('media_cat').' WHERE pid = '.$pid.' ORDER BY sort ASC');
	
if(in_array($action, ['edit', 'add'])) {
		
	echo '<form method="post" action="index.php">';
		
	$inputHidden = formInput::factory('action', 'save-'.$action);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('page', 'media');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('subpage', 'category');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('pid', $pid);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$buttonSubmit = formButton::factory('save', 'Artikel speichern');
	$buttonSubmit->addAttribute('type', 'submit');
	$buttonSubmit->addClass('btn-sm');
	$buttonSubmit->addClass('btn-default');
	
}

if($action == 'add') {
	
	$inputName = formInput::factory('name', '');
	$inputName->addAttribute('type', 'text');
	$inputName->addClass('input-sm');
	
	$inputSort = formInput::factory('sort', $table->getSql()->num()+1);
	$inputSort->addAttribute('type', 'text');
	$inputSort->addClass('input-sm');

	$table->addRow()
	->addCell($inputSort->get())
	->addCell($inputName->get())
	->addCell($buttonSubmit->get());	
	
}

while($table->isNext()) {
	
	if($action == 'edit' && $table->get('id') == $id) {
			
		$inputName = formInput::factory('name', $table->get('name'));
		$inputName->addAttribute('type', 'text');
		$inputName->addClass('input-sm');
		
		$inputSort = formInput::factory('sort', $table->get('sort'));
		$inputSort->addAttribute('type', 'text');
		$inputSort->addClass('input-sm');
		
		$inputHidden = formInput::factory('id', $table->get('id'));
		$inputHidden->addAttribute('type', 'hidden');
		
		$table->addRow()
		->addCell($inputSort->get())
		->addCell($inputName->get())
		->addCell($inputHidden->get().$buttonSubmit->get());
		
	} else {
		
		$edit = '<a href="'.url::backend('media', ['subpage'=>'category', 'action'=>'edit', 'id'=>$table->get('id'),'pid'=>$pid]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';	
		$delete = '<a href="'.url::backend('media', ['subpage'=>'category', 'action'=>'delete', 'id'=>$table->get('id'),'pid'=>$pid]).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
	
		$table->addRow(array('data-id'=>$table->get('id')))
		->addCell('<i class="icon-sort"></i>')
		->addCell('<a href="'.url::backend('media', ['subpage'=>'category', 'pid'=>$table->get('id')]).'">'.$table->get('name').'</a>')
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
	}
	
	$table->next();	
}

echo $table->show();


if(in_array($action, ['edit', 'add'])) {
	echo '</form>';
}

?>