<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

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
	$sql->getPosts(array('name'=>'string','sort'=>'int'));
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		$sql->save();	
	}
}

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
	
	if(in_array($action, array('edit', 'add'))) {
		echo '<form method="post" action="index.php">';
		echo '<input type="hidden" name="action" value="save-'.$action.'">';
		echo '<input type="hidden" name="page" value="structure">';
	}
	
	if($action == 'add') {
	
		$table->addRow()
		->addCell('<input type="text" name="name" value="" />')
		->addCell('<input type="text" name="sort" value="" />')
		->addCell('<button type="submit">Artikel speichern</button>');	
		
	}


	$table->setSql('SELECT * FROM structure ORDER BY sort ASC');
	while($table->isNext()) {
		
		if($action == 'edit') {
			
			$table->addRow()
			->addCell('<input type="text" name="name" value="'.$table->get('name').'" />')
			->addCell('<input type="text" name="sort" value="'.$table->get('sort').'" />')
			->addCell('<input type="hidden" name="id" value="'.$table->get('id').'" /><button type="submit">Artikel speichern</button>');
			
		} else {
			
			$edit = '<a href="'.url::backend('structure', array('action'=>'edit', 'id'=>$table->get('id'))).'" class="btn btn-small  btn-default">'.lang::get('edit').'</a>';
			$delete = '<a href="'.url::backend('structure', array('action'=>'delete', 'id'=>$table->get('id'))).'" class="btn btn-small btn-danger">'.lang::get('delete').'</a>';
			
			$online = ($table->get('online')) ? 'online' : 'offline';
			$online = '<a href="'.url::backend('structure', array('action'=>'online', 'id'=>$table->get('id'))).'" class="btn btn-small structure-'.$online.'">'.$online.'</a>';
		
			$table->addRow()
			->addCell($table->get('name'))
			->addCell($table->get('sort'))	
			->addCell($edit.' '.$delete.' '.$online);
			
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