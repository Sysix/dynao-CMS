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
	
	ajax::addReturn(message::success(lang::get('save_sorting'), true));
	
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
	
	sql::sortTable('structure', 0, '`parent_id` = '.$sql->get('parent_id'));
	
	echo message::success(lang::get('structure_delete'));
		
}

if($action == 'online') {

	$sql = sql::factory();
	$sql->query('SELECT online FROM '.sql::table('structure').' WHERE id='.$id)->result();
	
	$online = ($sql->get('online')) ? 0 : 1;
	
	$sql->setTable('structure');
	$sql->setWhere('id='.$id);
	$sql->addPost('online', $online);
	$sql->update();
	
	echo message::success(lang::get('save_status'));
	
}

if(in_array($action, ['save-add', 'save-edit'])) {
	
	$sql = sql::factory();
	$sql->setTable('structure');
	$sql->setWhere('id='.$id);
	$sql->getPosts([
		'name'=>'string',
		'sort'=>'int',
	]);
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {
		$sql->save();
	}
	
	$sort = type::post('sort', 'int');
	$parent_id = type::post('parent_id', 'int');
	
	//sql::sortTable('structure', $sort, '`parent_id` = '.$parent_id.' AND id != '.$id);
	
}
	
if(in_array($action, ['edit', 'add'])) {
		
	echo '<form method="post" action="index.php">';
		
	$inputHidden = formInput::factory('action', 'save-'.$action);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('page', 'structure');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
}

//echo $table->show();
echo PHP_EOL.'<div id="structure-tree" class="structure-root">'.PHP_EOL;
echo page::getTreeStructurePage();
echo '</div>';


if(in_array($action, ['edit', 'add'])) {
	echo '</form>';
}

layout::addJsCode("
$('#structure-tree').nestable({
	listNodeName: 'ul',
	rootClass: 'structure-root',
	listClass: 'list',
	itemClass: 'item',
	dragClass: 'dragel',
	handleClass: 'fa-sort',
	expandBtnHTML : '<button  data-action=\"expand\" class=\"fa fa-plus-square\"></button>',
	collapseBtnHTML: '<button data-action=\"collapse\" class=\"fa fa-minus-square\"></button>',
	placeClass: 'placeholder'
});
");

?>