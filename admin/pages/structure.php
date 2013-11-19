<?php

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);
$parent_id = type::super('parent_id', 'int', 0);
$structure_id = type::super('structure_id', 'int', $parent_id);
$subpage = type::super('subpage', 'string', 'pages');

backend::addSubnavi(lang::get('pages'),		url::backend('structure', ['subpage'=>'pages']), 		'home');
backend::addSubnavi('Slots',				url::backend('structure', ['subpage'=>'slots']), 		'th-large');
backend::addSubnavi(lang::get('modules'),	url::backend('structure', ['subpage'=>'module']),		'list-alt');

include_once(backend::getSubnaviInclude());

?>