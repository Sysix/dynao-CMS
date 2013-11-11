<?php

autoload::addClass(dir::addon('metainfos', 'lib/classes/metainfos.php'));

if(type::super('page', 'string') == 'addons') {
	backend::addSubnavi('Meta Infos', url::backend('meta'), 'plus');
}
?>