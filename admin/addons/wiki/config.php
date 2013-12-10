<?php

userPerm::add('wiki[edit]', lang::get('wiki[edit]'));
userPerm::add('wiki[delete]', lang::get('wiki[delete]'));

if(
	dyn::get('user')->hasPerm('wiki[edit]') ||
	dyn::get('user')->hasPerm('wiki[delete]')
) {
	backend::addAddonNavi(lang::get('wiki'), url::backend('wiki'), -1, function() {
		return dir::addon('wiki', 'page'.DIRECTORY_SEPARATOR.'wiki.php');
	});
}


?>