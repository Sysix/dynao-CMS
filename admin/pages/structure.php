<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);
$subpage = type::super('subpage', 'string', 'pages');
$structure_id = type::super('structure_id', 'int');

if($subpage == 'popup') {
	
	backend::addSubnavi('Popup',		url::backend('structure', ['subpage'=>'popup']));
	
}
if(
	dyn::get('user')->hasPerm('page[edit]') ||
	dyn::get('user')->hasPerm('page[delete]') ||
	dyn::get('user')->hasPerm('page[content]')
) {
	backend::addSubnavi(lang::get('pages'),		url::backend('structure', ['subpage'=>'pages']), 		'home');
	backend::addSubnavi(lang::get('blocks'),	url::backend('structure', ['subpage'=>'blocks']), 		'th-large');
}

if(dyn::get('user')->hasPerm('page[module]')) {
	backend::addSubnavi(lang::get('modules'),	url::backend('structure', ['subpage'=>'module']),		'list-alt');
}

include_once(backend::getSubnaviInclude());

?>