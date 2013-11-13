<?php

backend::addSubnavi('Medien verwalten',				url::backend('media', ['subpage'=>'files']));
backend::addSubnavi('Medienkategorien verwalten',	url::backend('media', ['subpage'=>'category']));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

if(type::super('subaction', 'string') == 'popup') {
	
	dyn::add('contentPage', true);
	backend::addSubnavi('Popup',		url::backend('media', ['subpage'=>'popup']));
	
}

include_once(backend::getSubnaviInclude('mediamanager'));

?>