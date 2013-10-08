<?php

backend::addSubnavi('Medien verwalten',				url::backend('media', array('subpage'=>'files')));
backend::addSubnavi('Medienkategorien verwalten',	url::backend('media', array('subpage'=>'category')));
backend::addSubnavi('Dateien Synchronisieren',		url::backend('media', array('subpage'=>'sync')));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

$allowPages = array('files', 'category', 'sync');
$allowPages = extension::get('page_subpages', $allowPages);

if(in_array($subpage, $allowPages)) {
	
	include_once(dir::addon('mediamanager', 'page/media.'.$subpage.'.php'));
	
} else {
	
	include_once(dir::addon('mediamanager', 'page/media.'.$allowPages[0].'.php'));
	
}

?>