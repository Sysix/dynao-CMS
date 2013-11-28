<?php

if(type::super('page', 'string') == 'addons') {
	backend::addSubnavi('Meta Infos', url::backend('meta'), 'plus');
}

userPerm::add('metainfos[edit]', lang::get('metainfos[edit]'));
userPerm::add('metainfos[delete]', lang::get('metainfos[delete]'));

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$secondpage = type::super('secondpage', 'string');
$action = type::super('action', 'string');

if($page == 'structure' && $secondpage == 'edit') {
	
	extension::add('FORM_BEFORE_ACTION', function($form) {
		$form = metainfos::getMetaInfos($form, 'structure');
	});
	
}

if(addonConfig::isActive('mediamanager') && $page == 'media' && $subpage == 'files' && in_array($action, ['add', 'edit'])) {
	
	extension::add('FORM_BEFORE_ACTION', function($form) {
		$form = metainfos::getMetaInfos($form, 'media');
	});	
		
}
?>