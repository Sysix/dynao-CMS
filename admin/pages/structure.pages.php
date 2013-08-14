<?php

if($action == 'delete') {
	
		$sql = new sql();
		$sql->setTable('structure');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		echo message::success('Artikel erfolgreich gelöscht');
		
}

if($action == 'online') {

	$sql = new sql();
	$sql->query('SELECT online FROM structure WHERE id='.$id)->result();
	
	$online = ($sql->get('online')) ? 0 : 1;
	
	$sql->setTable('structure');
	$sql->setWhere('id='.$id);
	$sql->addPost('online', $online);
	$sql->update();
	
	echo message::success('Status erfoglreich geändert');
	
}

if(in_array($action, array('save-add', 'save-edit'))) {
	
	$sql = new sql();
	$sql->setTable('structure');
	$sql->setWhere('id='.$id);
	$sql->getPosts(array('name'=>'string','sort'=>'int', 'parent_id'=>'int'));
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		$sql->save();	
	}
}

echo '<a href="'.url::backend('structure', array('action'=>'add', 'parent_id'=>$parent_id)).'" class="btn btn-small btn-primary pull-right">'.lang::get('add').'</a>';
echo '<div class="clearfix"></div>';

$table = new table();

$table->addCollsLayout('*, 50, 250');
	
$table->addRow()
->addCell('Artikel')
->addCell('Pos')
->addCell('Aktion');

$table->addSection('tbody');

$cacheFileName = cache::getFileName(1, 'structure');

if(cache::exist($cacheFileName) && !in_array($action, array('edit', 'add'))) {
	
	echo cache::read($cacheFileName);
	
} else {
	
	$table->setSql('SELECT * FROM structure WHERE parent_id = '.$parent_id.' ORDER BY sort ASC');
	
	if(in_array($action, array('edit', 'add'))) {
		
		echo '<form method="post" action="index.php">';
		
		$inputHidden = new formInput('action', 'save-'.$action);
		$inputHidden->addAttribute('type', 'hidden');
		echo $inputHidden->get();
		
		$inputHidden = new formInput('page', 'structure');
		$inputHidden->addAttribute('type', 'hidden');
		echo $inputHidden->get();
		
		$inputHidden = new formInput('parent_id', $parent_id);
		$inputHidden->addAttribute('type', 'hidden');
		echo $inputHidden->get();
		
		$buttonSubmit = new formButton('save', 'Artikel speichern');
		$buttonSubmit->addAttribute('type', 'submit');
		$buttonSubmit->addClass('btn');
		$buttonSubmit->addClass('btn-small');
		$buttonSubmit->addClass('btn-default');
		
	}
	
	if($action == 'add') {
		
		$inputName = new formInput('name', '');
		$inputName->addAttribute('type', 'text');
		$inputName->addClass('form-control');
		$inputName->addClass('input-small');
		
		$inputSort = new formInput('sort', '');
		$inputSort->addAttribute('type', 'text');
		$inputSort->addClass('form-control');
		$inputSort->addClass('input-small');
	
		$table->addRow()
		->addCell($inputName->get())
		->addCell($inputSort->get())
		->addCell($buttonSubmit->get());	
		
	}

	while($table->isNext()) {
		
		if($action == 'edit' && $table->get('id') == $id) {
			
			$inputName = new formInput('name', $table->get('name'));
			$inputName->addAttribute('type', 'text');
			$inputName->addClass('form-control');
			$inputName->addClass('input-small');
			
			$inputSort = new formInput('sort', $table->get('sort'));
			$inputSort->addAttribute('type', 'text');
			$inputSort->addClass('form-control');
			$inputSort->addClass('input-small');
			
			$inputHidden = new formInput('id', $table->get('id'));
			$inputHidden->addAttribute('type', 'hidden');
			
			$table->addRow()
			->addCell($inputName->get())
			->addCell($inputSort->get())
			->addCell($inputHidden->get().$buttonSubmit->get());
			
		} else {
			
			$edit = '<a href="'.url::backend('structure', array('action'=>'edit', 'id'=>$table->get('id'),'parent_id'=>$parent_id)).'" class="btn btn-small  btn-default">'.lang::get('edit').'</a>';	
			$delete = '<a href="'.url::backend('structure', array('action'=>'delete', 'id'=>$table->get('id'),'parent_id'=>$parent_id)).'" class="btn btn-small btn-danger">'.lang::get('delete').'</a>';
			
			$online = ($table->get('online')) ? 'online' : 'offline';
			$online = '<a href="'.url::backend('structure', array('action'=>'online', 'id'=>$table->get('id'),'parent_id'=>$parent_id)).'" class="btn btn-small structure-'.$online.'">'.$online.'</a>';
		
			$table->addRow()
			->addCell('<a href="'.url::backend('structure', array('parent_id'=>$table->get('id'))).'">'.$table->get('name').'</a>')
			->addCell($table->get('sort'))	
			->addCell($edit.$delete.$online, array('class'=>'btn-group'));
			
		}
		
		$table->next();	
	}
	
	$show = $table->show();
	
	cache::write($show, $cacheFileName);
	
	echo $show;
	
	if(in_array($action, array('edit', 'add'))) {
		echo '</form>';
	}

}

?>