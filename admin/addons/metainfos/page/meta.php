<?php

backend::addSubNavi(lang::get('article'),	url::backend('meta', ['subpage'=>'structure']), 'circle', 0);



$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

include_once(backend::getSubnaviInclude('metainfos'));

?>