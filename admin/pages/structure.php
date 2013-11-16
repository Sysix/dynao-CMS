<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);
$parent_id = type::super('parent_id', 'int', 0);
$structure_id = type::super('structure_id', 'int', $parent_id);
$subpage = type::super('subpage', 'string', 'pages');

backend::addSubnavi(lang::get('pages'),	url::backend('structure', ['parent_id'=>$parent_id, 'subpage'=>'pages']), 				'home');
#backend::addSubnavi('Inhalt',		url::backend('structure', ['structure_id'=>$structure_id, 'subpage'=>'content']),		'edit');
backend::addSubnavi(lang::get('modules'),		url::backend('structure', ['subpage'=>'module']),										'list-alt');

layout::addJS('layout/js/nestable.js');


$breadcrumb = [];

$while_id = $parent_id;

while($while_id) {
		
	$sql = sql::factory();
	$sql->query('SELECT name, parent_id FROM '.sql::table('structure').' WHERE id='.$while_id)->result();
	
	if($parent_id != $while_id) {
		
		$breadcrumb[] = '<li><a href="'.url::backend('structure', ['parent_id'=>$while_id, 'subpage'=>$subpage]).'">'.$sql->get('name').'</a></li>';
		
	} else {
		
		$breadcrumb[] = '<li class="active">'.$sql->get('name').'</li>';
		
	}
	
	$while_id = $sql->get('parent_id');
	
}

$breadcrumb[] = '<li><a href="'.url::backend('structure').'">'.lang::get('structure').'</a></li>';

#echo '<ul class="pull-left breadcrumb">'.implode('', array_reverse($breadcrumb)).'</ul>';

include_once(backend::getSubnaviInclude());

?>