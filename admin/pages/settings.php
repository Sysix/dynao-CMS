<?php

if(!dyn::get('user')->isAdmin()) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

backend::addSubnavi(lang::get('general'),	url::backend('settings', ['subpage'=>'main']),	'eye');
	
include_once(backend::getSubnaviInclude());
?>