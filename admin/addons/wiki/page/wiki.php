<?php

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');
	
backend::addSubNavi(lang::get('categories'), url::backend('wiki', ['subpage'=>'category']));	
backend::addSubNavi(lang::get('articles'),	url::backend('wiki', ['subpage'=>'article']));

include_once(backend::getSubnaviInclude('wiki'));

?>