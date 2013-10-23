<?php

backend::addSubnavi('Artikel',						url::backend('meta', array('subpage'=>'articles')));
backend::addSubnavi('Medien',						url::backend('meta', array('subpage'=>'media')));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

$allowPages = array('articles', 'media');
$allowPages = extension::get('page_subpages', $allowPages);

require(dir::addon('metainfos', 'lib/classes/metainfosPage.php'));

if(in_array($subpage, $allowPages)) {
	
	include_once(dir::addon('metainfos', 'page/meta.'.$subpage.'.php'));
	
} else {
	
	include_once(dir::addon('metainfos', 'page/meta.'.$allowPages[0].'.php'));
	
}

?>