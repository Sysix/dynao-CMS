<?php

echo '<select class="form-control">';
echo page::getTreeStructure();
echo '</select>';
echo '<a href="'.url::backend('structure', ['action'=>'add', 'parent_id'=>$parent_id]).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
echo '<div class="clearfix"></div>';

if(ajax::is()) {
	
	$sort = type::post('array', 'array');
	
	$sql = sql::factory();
	$sql->setTable('structure');
	foreach($sort as $s=>$id) {
		$sql->setWhere('id='.$id);
		$sql->addPost('sort', $s+1);
		$sql->update();	
	}
	
	ajax::addReturn(message::success('Sortierung erfolgreich übernommen', true));
	
}

if($action == 'delete') {
	
	$orginal_id = $id;
	
	while($id) {
	
		$sql = sql::factory();
		$sql->query('SELECT id FROM '.sql::table('structure').' WHERE `parent_id` = '.$id)->result();
		if($sql->num()) {
			
			$id = $sql->get('id');
			
			$delete = sql::factory();
			$delete->setTable('structure');
			$delete->setWhere('id='.$id);
			$delete->delete();			
			
		} else {
			$id = false;	
		}
		
	}
	
	
	$sql = sql::factory();		
	$sql->query('SELECT `sort`, `parent_id` FROM '.sql::table('structure').' WHERE id='.$orginal_id)->result();
	
	$delete = sql::factory();
	$delete->setTable('structure');
	$delete->setWhere('id='.$orginal_id);
	$delete->delete();
	
	sql::sortTable('structure', $sql->get('sort'), false, '`parent_id` = '.$sql->get('parent_id'));
	
	echo message::success('Artikel erfolgreich gelöscht');
		
}

if($action == 'online') {

	$sql = sql::factory();
	$sql->query('SELECT online FROM '.sql::table('structure').' WHERE id='.$id)->result();
	
	$online = ($sql->get('online')) ? 0 : 1;
	
	$sql->setTable('structure');
	$sql->setWhere('id='.$id);
	$sql->addPost('online', $online);
	$sql->update();
	
	echo message::success('Status erfoglreich geändert');
	
}

if(in_array($action, ['save-add', 'save-edit'])) {
	
	$sql = sql::factory();
	$sql->setTable('structure');
	$sql->setWhere('id='.$id);
	$sql->getPosts(['name'=>'string','sort'=>'int', 'parent_id'=>'int']);
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		sql::sortTable('structure', type::post('sort', 'int'), true, '`parent_id` = '.type::post('parent_id', 'int'));
		$sql->save();	
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
	
$table->setSql('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parent_id.' ORDER BY sort ASC');
	
if(in_array($action, ['edit', 'add'])) {
		
	echo '<form method="post" action="index.php">';
		
	$inputHidden = formInput::factory('action', 'save-'.$action);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('page', 'structure');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('parent_id', $parent_id);
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
	
	$sql = sql::factory();
	$inputSort = formInput::factory('sort', $sql->num('SELECT 1 FROM '.sql::table('structure').' WHERE `parent_id`= '.type::super('parent_id', 'int'))+1);
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
		
		$edit = '<a href="'.url::backend('structure', ['action'=>'edit', 'id'=>$table->get('id'),'parent_id'=>$parent_id]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';	
		$delete = '<a href="'.url::backend('structure', ['action'=>'delete', 'id'=>$table->get('id'),'parent_id'=>$parent_id]).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
		
		$online = ($table->get('online')) ? 'online' : 'offline';
		$online = '<a href="'.url::backend('structure', ['action'=>'online', 'id'=>$table->get('id'),'parent_id'=>$parent_id]).'" class="btn btn-sm structure-'.$online.'">'.$online.'</a>';
	
		$table->addRow(['data-id'=>$table->get('id')])
		->addCell('<i class="icon-sort"></i>')
		->addCell('<a href="'.url::backend('structure', ['parent_id'=>$table->get('id')]).'">'.$table->get('name').'</a>')
		->addCell('<span class="btn-group">'.$edit.$delete.$online.'</span>');
		
	}
	
	$table->next();	
}

echo $table->show();


if(in_array($action, ['edit', 'add'])) {
	echo '</form>';
}

?>