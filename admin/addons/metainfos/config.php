<?php

autoload::addClass(dir::addon('metainfos', 'lib/classes/metainfos.php'));

if(type::super('page', 'string') == 'addons') {
	backend::addSubnavi('Meta Infos', url::backend('meta'), 'plus');
}

userPerm::add('metainfos[edit]', 'Metadaten bearbeiten / erstellen');
userPerm::add('metainfos[delete]', 'Metadaten löschen');

?>