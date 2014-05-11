<?php
$action = type::super('action', 'string');
$addon = type::super('addon', 'string');

backend::addSubnavi(lang::get('overview'),	url::backend('addons', ['subpage'=>'overview']), 0);

include_once(backend::getSubnaviInclude());

?>