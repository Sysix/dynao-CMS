<?php

backend::addSecondnavi(lang::get('article'),	url::backend('addons', ['subpage'=>'meta', 'secondpage'=>'articles']), -1, function() {
	return dir::addon('metainfos', 'page/meta.articles.php');
});

if(addonConfig::isActive('mediamanager')) {
	
	backend::addSecondnavi(lang::get('media'),	url::backend('addons', ['subpage'=>'meta', 'secondpage'=>'media']), -1, function() {
		return dir::addon('metainfos', 'page/meta.media.php');
	});
		
}

backend::setCurrents();

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

include_once(backend::getSecondnaviInclude('metainfos'));

?>