<?php

ob_start();
ob_implicit_flush(0);
mb_internal_encoding('UTF-8');
session_start();

include('lib/classes/dir.php');

if(isset($DYN['root'])) {
	new dir($DYN['root']);
} else {
	new dir('../');
}

include(dir::classes('autoload.php'));
autoload::register();
autoload::addDir('utils');

new dyn();

if(isset($DYN['backend'])) {
	dyn::add('backend', $DYN['backend']);	
} else {
	dyn::add('backend', true);
}

unset($DYN);

include(dir::functions('html_stuff.php'));
include(dir::functions('url_stuff.php'));

lang::setDefault();
lang::setLang(dyn::get('lang'));

$DB = dyn::get('DB');
sql::connect($DB['host'], $DB['user'], $DB['password'], $DB['database']);

	
new userLogin();
dyn::add('user', new user());

cache::setCache(dyn::get('cache'));

ajax::convertPOST();

foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

addonConfig::includeAllLangFiles();

if(dyn::get('backend')) {
	
	include(dir::backend('backend.php'));
	
} else {
	
	include(dir::backend('frontend.php'));
	
}


?>