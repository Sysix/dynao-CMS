<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);
$parent_id = type::super('parent_id', 'int', 0);
$subpage = type::super('subpage', 'string', 'pages');

backend::addSubnavi('Unterseiten',	url::backend('structure', array('parent_id'=>$parent_id, 'subpage'=>'pages')), 		'home');
backend::addSubnavi('Inhalt',		url::backend('structure', array('parent_id'=>$parent_id, 'subpage'=>'content')),		'edit');
backend::addSubnavi('Meta Daten',	url::backend('structure', array('parent_id'=>$parent_id, 'subpage'=>'meta')));


$breadcrumb = array();

$while_id = $parent_id;

while($while_id) {
		
	$sql = new sql();
	$sql->query('SELECT name, parent_id FROM structure WHERE id='.$while_id)->result();
	
	if($parent_id != $while_id) {
		
		$breadcrumb[] = '<li><a href="'.url::backend('structure', array('parent_id'=>$while_id, 'subpage'=>$subpage)).'">'.$sql->get('name').'</a></li>';
		
	} else {
		
		$breadcrumb[] = '<li class="active">'.$sql->get('name').'</li>';
		
	}
	
	$while_id = $sql->get('parent_id');
	
}

$breadcrumb[] = '<li><a href="'.url::backend('structure').'">Struktur</a></li>';

echo '<ul class="pull-left breadcrumb">'.implode('', array_reverse($breadcrumb)).'</ul>';


$allowPages = array('pages', 'content', 'meta');
$allowPages = extension::get('page_subpages', $allowPages);

if(in_array($subpage, $allowPages)) {
	
	include_once(dir::page('structure.'.$subpage.'.php'));
	
} else {
	
	include_once(dir::page('structure.'.$allowPages[0].'.php'));
	
}

?>