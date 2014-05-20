<?php
backend::addSubNavi(lang::get('import'),url::backend('import', ['subpage'=>'import']), 1);
backend::addSubNavi(lang::get('export'),url::backend('import', ['subpage'=>'export']), 2);
$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');
include_once(backend::getSubnaviInclude('imexport'));
?>