<?php

if(type::super('page', 'string') == 'addons') {
	backend::addSubnavi('Meta Infos', url::backend('meta'), 'plus');
}

userPerm::add('metainfos[edit]', lang::get('metainfos[edit]'));
userPerm::add('metainfos[delete]', lang::get('metainfos[delete]'));

?>