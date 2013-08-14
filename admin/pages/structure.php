<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);
$parent_id = type::super('parent_id', 'int', 0);
$subpage = type::super('subpage', 'string', 'pages');

backend::addSubnavi('Unterseiten', url::backend('structure', array('subpage'=>'pages')), 'home');
backend::addSubnavi('Inhalt', 'index.php?'.url_addParam('subpage', 'content'), 'edit');
backend::addSubnavi('Meta Daten', 'index.php?'.url_addParam('subpage', 'meta'));

function structure_addSEO($pages) {

	$pages[] = 'seo';
	backend::addSubnavi('SEO', 'index.php?'.url_addParam('subpage', 'seo'), 'star');
	return $pages;
	
}

extension::add('page_subpages', 'structure_addSEO');


$allowPages = array('pages', 'content', 'meta');
$allowPages = extension::get('page_subpages', $allowPages);

if(in_array($subpage, $allowPages)) {
	
	include_once(dir::page('structure.'.$subpage.'.php'));
	
} else {
	
	include_once(dir::page('structure.'.$allowPages[0].'.php'));
	
}

?>