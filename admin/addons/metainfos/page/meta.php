<?php

backend::addSubnavi('Artikel',						url::backend('meta', ['subpage'=>'articles']));
backend::addSubnavi('Medien',						url::backend('meta', ['subpage'=>'media']));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

include_once(backend::getSubnaviInclude('metainfos'));

?>