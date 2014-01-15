<?php

backend::addSubNavi('F.A.Q', url::backend('seo', ['subpage'=>'faq']), 'question');
backend::addSubNavi(lang::get('settings'), url::backend('seo', ['subpage'=>'settings']), 'gear');

$action = type::super('action', 'string', '');

include_once(backend::getSubnaviInclude('seo'));

?>