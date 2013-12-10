<?php

userPerm::add('wiki[edit]', lang::get('wiki[edit]'));
userPerm::add('wiki[delete]', lang::get('wiki[delete]'));

if(
	dyn::get('user')->hasPerm('wiki[edit]') ||
	dyn::get('user')->hasPerm('wiki[delete]')
) {
	backend::addAddonNavi(lang::get('wiki'), url::backend('wiki'));
}

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');

if($page == 'wiki') {
	
	backend::addSubNavi(lang::get('categories'),	url::backend('wiki', ['subpage'=>'category']), 'circle', -1, function() {
		return dir::addon('wiki', 'page'.DIRECTORY_SEPARATOR.'wiki.category.php');
	});
	
	backend::addSubNavi(lang::get('articles'),	url::backend('wiki', ['subpage'=>'article']), 'circle', -1, function() {
		return dir::addon('wiki', 'page'.DIRECTORY_SEPARATOR.'wiki.article.php');
	});
	
	include_once(backend::getSubnaviInclude('wiki'));
}

?>