<?php

backend::addSubNavi('User', url::backend('user', ['subpage'=>'user']), 'users');

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

include_once(backend::getSubnaviInclude('community'));

?>