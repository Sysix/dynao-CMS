<?php

if(!dyn::get('backend')) {
	
	if(!is_null(type::post('community_login', 'string'))) {
		community_user::checkLogin();
	} else {
		community_user::checkSession();
	}
	
} else {
	
	userPerm::add('coummunity[]', 'Community Administrator');
	
	backend::addAddonNavi(lang::get('community'), url::backend('community'), -1, function() {
        return dir::addon('community', 'page'.DIRECTORY_SEPARATOR.'community.php');
    });
	
}

?>