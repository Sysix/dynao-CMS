<?php
if(
	dyn::get('user')->hasPerm('media[edit]') ||
	dyn::get('user')->hasPerm('media[delete]')
) {
	backend::addSubnavi(lang::get('media_manage'),				url::backend('media', ['subpage'=>'files']));
}
if(
	dyn::get('user')->hasPerm('media[category][edit]') ||
	dyn::get('user')->hasPerm('media[category][delete]')
) {
	backend::addSubnavi(lang::get('media_manage_cat'),			url::backend('media', ['subpage'=>'category']));
}

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);
$subpage = type::super('subpage', 'string');

if($subpage == 'popup') {
	
	dyn::add('contentPage', true);
	backend::addSubnavi('Popup',		url::backend('media', ['subpage'=>'popup']));
	
}

include_once(backend::getSubnaviInclude('mediamanager'));

?>