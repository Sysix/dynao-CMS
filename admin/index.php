<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_implicit_flush(0);
mb_internal_encoding('UTF-8');
session_start();

if(version_compare(PHP_VERSION, 5.4) < 0) {
    echo 'PHP version 5.4 or higher needed!';
	exit();
}

include('lib'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'dir.php');

if(isset($DYN['root'])) {
	new dir($DYN['root']);
} else {
	new dir();
}

include(dir::classes('autoload.php'));
autoload::register();
autoload::addDir(dir::classes('utils'));

new dyn();

if(dyn::get('setup') == true) {
	header('Location: install/');
	exit();
}

if(isset($DYN['backend'])) {
	dyn::add('backend', $DYN['backend']);
} else {
	dyn::add('backend', true);
}

unset($DYN);

include(dir::functions('html_stuff.php'));
include(dir::functions('url_stuff.php'));


$DB = dyn::get('DB');
sql::connect($DB['host'], $DB['user'], $DB['password'], $DB['database']);

lang::init();
url::init();

ob_start();

date_default_timezone_set(dyn::get('timezone', 'Europe/Berlin'));

new userLogin();
dyn::add('user', new user(userLogin::getUser()));

cache::setCache(dyn::get('cache'));

addonConfig::loadAllConfig();
addonConfig::includeAllLangFiles();
addonConfig::includeAllLibs();

if(dyn::get('backend')) {

	include(dir::backend('backend.php'));

} else {

	include(dir::backend('frontend.php'));

}

?>