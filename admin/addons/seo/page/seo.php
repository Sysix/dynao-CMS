<?php

backend::addSubNavi('F.A.Q', url::backend('seo', ['subpage'=>'faq']));
backend::addSubNavi(lang::get('settings'), url::backend('seo', ['subpage'=>'settings']));
backend::addSubNavi(lang::get('setup'), url::backend('seo', ['subpage'=>'setup']));

$action = type::super('action', 'string', '');

include_once(backend::getSubnaviInclude('seo'));

?>