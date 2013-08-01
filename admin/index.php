<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
session_start();

include_once('lib/classes/dir.php');

include_once('lib/classes/autoload.php');
autoload::register();

lang::setDefault();
lang::setLang('de_de');

mb_internal_encoding('UTF-8');

sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

cache::setCache(false);

$page = type::super('page', 'string', 'dashboard');
$subpage = type::super('subpage', 'string');

ob_start();

$login = new userLogin();

if($login->isLogged()) {
	include(dir::page($page.'.php'));
}

$CONTENT = ob_get_contents();

ob_end_clean();

if($login->isLogged()) {
include(dir::backend('layout/index.php'));
} else {
	include(dir::backend('layout/login.php'));
}
?>