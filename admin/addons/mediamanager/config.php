<?php

layout::addJs('addons/mediamanager/layout/js/mediamanager.js');

userPerm::add('media[edit]', 'Medien bearbeiten / erstellen');
userPerm::add('media[delete]', 'Medien löschen');
userPerm::add('media[category][edit]', 'Medienkategorien bearbeiten / erstellen');
userPerm::add('media[category][delete]', 'Medienkategorien löschen');

if(
	dyn::get('user')->hasPerm('media[edit]') ||
	dyn::get('user')->hasPerm('media[delete]') ||
	dyn::get('user')->hasPerm('media[category][edit]') ||
	dyn::get('user')->hasPerm('media[category][delete]')
) {
	backend::addNavi('Media', url::backend('media'), 'picture-o', 2);
}

form::addClassMethod('addMediaField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMedia');
	
});

form::addClassMethod('addMediaListField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMediaList');
	
});
$page = type::super('page', 'string');

if($page == "media" && $subpage == "popup") {

	dyn::add('ajaxContinue', true);

}

pageArea::addType('MEDIA', 10);

if(addonConfig::isActive('metainfos')) {
	
	metainfosPage::addType('DYN_MEDIA');
	metainfosPage::addType('DYN_MEDIA_LIST');
		
}

?>