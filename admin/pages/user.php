<?php
if(!dyn::get('user')->hasPerm('admin[user]')) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

backend::addSubnavi(lang::get('overview'),		url::backend('user', ['subpage'=>'overview']), 		'eye');

include_once(backend::getSubnaviInclude());
?>