<?php

layout::addCSS('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Open+Sans+Condensed:300,700');
layout::addCSS('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css');
layout::addCSS('http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css');

layout::addCSS('layout/css/style.css');
layout::addCSS('layout/css/mobile.css');

layout::addJS('http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js');
layout::addJS('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
layout::addJS('http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js');
layout::addJS('layout/js/scripts.js');

userPerm::add('page[edit]', lang::get('page[edit]'));
userPerm::add('page[delete]', lang::get('page[delete]'));
userPerm::add('page[content]', lang::get('page[content]'));
userPerm::add('page[module]', lang::get('page[module]'));
userPerm::add('admin[user]', lang::get('admin[user]'));
userPerm::add('admin[addon]', lang::get('admin[addon]'));

backend::addNavi(lang::get('dashboard'), url::backend('dashboard'), 'desktop');

if(
	dyn::get('user')->hasPerm('page[edit]') ||
	dyn::get('user')->hasPerm('page[delete]') ||
	dyn::get('user')->hasPerm('page[content]') ||
	dyn::get('user')->hasPerm('page[module]')
) {
	backend::addNavi(lang::get('content'), url::backend('structure'), 'list');
}

if(dyn::get('user')->hasPerm('admin[user]')) {
	backend::addNavi(lang::get('user'), url::backend('user'), 'user');
}

if(dyn::get('user')->hasPerm('admin[addon]')) {
	backend::addNavi(lang::get('addons'), url::backend('addons'), 'code-fork');
}

if(dyn::get('user')->isAdmin()) {
	backend::addNavi(lang::get('settings'), url::backend('settings'), 'cogs');
}

foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

$page = type::super('page', 'string', 'dashboard');
$subpage = type::super('subpage', 'string');

$successMsg = type::get('success_msg', 'string');
$errorMsg = type::get('error_msg', 'string');

if(!is_null($errorMsg)) {
	echo message::danger($errorMsg);	
} elseif(!is_null($successMsg)) {
	echo message::success($successMsg);	
}

if(userLogin::isLogged()) {
	if($file = backend::getNaviInclude()) {
		include($file);	
	}
}

$content = ob_get_contents();

ob_end_clean();

$content = extension::get('BACKEND_OUTPUT', $content);

dyn::add('content', $content);

if(ajax::is()) {
	$deleteAction = type::get('deleteAction', 'bool', false);
	
	if($deleteAction) {
		$title = type::get('title', 'string');
		$message = type::get('message', 'string');
		getDeleteModal($title, $message);
	}
	
	echo ajax::getReturn();
	die;
}

if(userLogin::isLogged()) {	
	include(dir::backend('layout/index.php'));	
} else {
	include(dir::backend('layout/login.php'));
}

?>