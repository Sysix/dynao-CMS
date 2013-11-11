<?php

backend::addSubnavi('Medien verwalten',				url::backend('media', ['subpage'=>'files']));
backend::addSubnavi('Medienkategorien verwalten',	url::backend('media', ['subpage'=>'category']));
backend::addSubnavi('Dateien Synchronisieren',		url::backend('media', ['subpage'=>'sync']));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

include_once(backend::getSubnaviInclude('mediamanager'));

?>