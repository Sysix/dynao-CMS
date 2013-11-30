<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);
$subpage = type::super('subpage', 'string', 'pages');

if($subpage == 'popup') {
	
	dyn::add('contentPage', true);
	backend::addSubnavi('Popup',		url::backend('structure', ['subpage'=>'popup']));
	
}
if(
	dyn::get('user')->hasPerm('page[edit]') ||
	dyn::get('user')->hasPerm('page[delete]') ||
	dyn::get('user')->hasPerm('page[content]')
) {
	backend::addSubnavi(lang::get('pages'),		url::backend('structure', ['subpage'=>'pages']), 		'home');
	backend::addSubnavi(lang::get('slots'),		url::backend('structure', ['subpage'=>'slots']), 		'th-large');
}

if(dyn::get('user')->hasPerm('page[module]')) {
	backend::addSubnavi(lang::get('modules'),	url::backend('structure', ['subpage'=>'module']),		'list-alt');
}

include_once(backend::getSubnaviInclude());

?>