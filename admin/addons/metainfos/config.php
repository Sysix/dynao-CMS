<?php

userPerm::add('metainfos[edit]', lang::get('metainfos[edit]'));
userPerm::add('metainfos[delete]', lang::get('metainfos[delete]'));

if(
	dyn::get('user')->hasPerm('metainfos[edit]') ||
	dyn::get('user')->hasPerm('metainfos[delete]')
) {
	backend::addAddonNavi(lang::get('metainfos'), url::backend('meta'));
}

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');

if($page == 'structure' && $action == 'edit') {
	
	extension::add('FORM_BEFORE_ACTION', function($form) {
		$form = metainfos::getMetaInfos($form, 'structure');
	});
	
}
?>