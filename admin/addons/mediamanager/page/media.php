<?php

backend::addSubnavi(lang::get('media_edit'), url::backend('media', ['subpage'=>'files']));
backend::addSubnavi(lang::get('media_edit_cat'), url::backend('media', ['subpage'=>'category']));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

if($subpage == 'popup') {
	
	dyn::add('contentPage', true);
	backend::addSubnavi('Popup',		url::backend('media', ['subpage'=>'popup']));
	
}

include_once(backend::getSubnaviInclude('mediamanager'));

?>