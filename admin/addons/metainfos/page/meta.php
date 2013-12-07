<?php

backend::addSubNavi(lang::get('article'),	url::backend('meta', ['subpage'=>'structure']));

if(addonConfig::isActive('mediamanager')) {
	
	backend::addSubNavi(lang::get('media'),	url::backend('meta', ['subpage'=>'media']));
		
}

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

include_once(backend::getSubnaviInclude('metainfos'));

?>