<?php

$pid = type::super('pid', 'int', 0);

$while_id = $id;

while($while_id) {
		
	$sql = new sql();
	$sql->query('SELECT name, pid FROM '.sql::table('media_cat').' WHERE id='.$while_id)->result();
	
	if($id != $while_id) {
		
		$breadcrumb[] = '<li><a href="'.url::backend('media', array('id'=>$while_id, 'subpage'=>'category')).'">'.$sql->get('name').'</a></li>';
		
	} else {
		
		$breadcrumb[] = '<li class="active">'.$sql->get('name').'</li>';
		
	}
	
	$while_id = $sql->get('pid');
	
}

$breadcrumb[] = '<li><a href="'.url::backend('media', array('subpage'=>'category')).'">Homepage</a></li>';

echo '<ul class="pull-left breadcrumb">'.implode('', array_reverse($breadcrumb)).'</ul>';

echo '<a href="'.url::backend('media', array('subpage'=>'category', 'action'=>'add', 'id'=>$id)).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
echo '<div class="clearfix"></div>';

if($action == 'delete') {
	
	$orginal_id = $id;
	
	while($id) {
	
		$sql = new sql();
		$sql->query('SELECT id FROM '.sql::table('media_cat').' WHERE `pid` = '.$id)->result();
		if($sql->num()) {
			
			$id = $sql->get('id');
			
			$delete = new sql();
			$delete->setTable('media_cat');
			$delete->setWhere('id='.$id);
			$delete->delete();			
			
		} else {
			$id = false;	
		}
		
	}
	
	
	$sql = new sql();		
	$sql->query('SELECT `sort`, `pid` FROM '.sql::table('media_cat').' WHERE id='.$orginal_id)->result();
	
	$delete = new sql();
	$delete->setTable('media_cat');
	$delete->setWhere('id='.$orginal_id);
	$delete->delete();
	
	sql::sortTable('media_cat', $sql->get('sort'), false, '`pid` = '.$sql->get('pid'));
	
	echo message::success('Artikel erfolgreich gelöscht');
		
}

if(in_array($action, array('save-add', 'save-edit'))) {
	
	$sql = new sql();
	$sql->setTable('media_cat');
	$sql->setWhere('id='.$id);
	$sql->getPosts(array('name'=>'string','sort'=>'int', 'pid'=>'int'));
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		$sql->save();
		sql::sortTable('media_cat', type::post('sort', 'int'), true, '`pid` = '.type::post('pid', 'int'));	
	}
	
	
}

$table = new table(array('class'=>array('js-sort')));

$colFirstWidth = ($action == 'edit' || $action == 'add') ? 50 : 25; 

$table->addCollsLayout($colFirstWidth.',*,250');
	
$table->addRow()
->addCell()
->addCell('Artikel')
->addCell('Aktion');

$table->addSection('tbody');
	
$table->setSql('SELECT * FROM '.sql::table('media_cat').' WHERE pid = '.$pid.' ORDER BY sort ASC');
	
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
	
	$inputHidden = new formInput('pid', $pid);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$buttonSubmit = new formButton('save', 'Artikel speichern');
	$buttonSubmit->addAttribute('type', 'submit');
	$buttonSubmit->addClass('btn-sm');
	$buttonSubmit->addClass('btn-default');
	
}

if($action == 'add') {
	
	$inputName = new formInput('name', '');
	$inputName->addAttribute('type', 'text');
	$inputName->addClass('input-sm');
	
	$inputSort = new formInput('sort', $table->getSql()->num()+1);
	$inputSort->addAttribute('type', 'text');
	$inputSort->addClass('input-sm');

	$table->addRow()
	->addCell($inputSort->get())
	->addCell($inputName->get())
	->addCell($buttonSubmit->get());	
	
}

while($table->isNext()) {
	
	if($action == 'edit' && $table->get('id') == $id) {
			
		$inputName = new formInput('name', $table->get('name'));
		$inputName->addAttribute('type', 'text');
		$inputName->addClass('input-sm');
		
		$inputSort = new formInput('sort', $table->get('sort'));
		$inputSort->addAttribute('type', 'text');
		$inputSort->addClass('input-sm');
		
		$inputHidden = new formInput('id', $table->get('id'));
		$inputHidden->addAttribute('type', 'hidden');
		
		$table->addRow()
		->addCell($inputSort->get())
		->addCell($inputName->get())
		->addCell($inputHidden->get().$buttonSubmit->get());
		
	} else {
		
		$edit = '<a href="'.url::backend('media', array('subpage'=>'category', 'action'=>'edit', 'id'=>$table->get('id'),'pid'=>$pid)).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';	
		$delete = '<a href="'.url::backend('media', array('subpage'=>'category', 'action'=>'delete', 'id'=>$table->get('id'),'pid'=>$pid)).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
	
		$table->addRow(array('data-id'=>$table->get('id')))
		->addCell('<i class="icon-sort"></i>')
		->addCell('<a href="'.url::backend('media', array('subpage'=>'category', 'pid'=>$table->get('id'))).'">'.$table->get('name').'</a>')
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
	}
	
	$table->next();	
}

echo $table->show();


if(in_array($action, array('edit', 'add'))) {
	echo '</form>';
}

?>