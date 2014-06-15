<?php
backend::addSubNavi(lang::get('import'),url::backend('import', ['subpage'=>'import']));
backend::addSubNavi(lang::get('export'),url::backend('import', ['subpage'=>'export']));

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');

include_once(backend::getSubnaviInclude('imexport'));
?>