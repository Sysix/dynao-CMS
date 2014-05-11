<?php

if(!dyn::get('user')->isAdmin()) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');

backend::addSubnavi(lang::get('general'),	url::backend('settings', ['subpage'=>'main']));
	
include_once(backend::getSubnaviInclude());
?>