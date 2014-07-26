<?php

layout::addJs('addons/mediamanager/layout/js/mediamanager.js');
layout::addCSS('addons/mediamanager/layout/css/mediamanager.css');

userPerm::add('media[edit]', lang::get('media[edit]'));
userPerm::add('media[delete]', lang::get('media[delete]'));
userPerm::add('media[category][edit]', lang::get('media[category][edit]'));
userPerm::add('media[category][delete]', lang::get('media[category][delete]'));

if(
	dyn::get('user')->hasPerm('media[edit]') ||
	dyn::get('user')->hasPerm('media[delete]') ||
	dyn::get('user')->hasPerm('media[category][edit]') ||
	dyn::get('user')->hasPerm('media[category][delete]')
) {
	backend::addNavi(lang::get('media'), url::backend('media'), 'picture-o', 2, function() {
		return dir::addon('mediamanager', 'page'.DIRECTORY_SEPARATOR.'media.php');
	});
}

form::addClassMethod('addMediaField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMedia');
	
});

form::addClassMethod('addMediaListField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMediaList');
	
});

$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');
$action = type::super('action', 'string');

pageArea::addType('varsMedia');
pageArea::addType('varsMedialist');

if(addonConfig::isActive('metainfos')) {
	
	metainfosPage::addType('DYN_MEDIA');
	metainfosPage::addType('DYN_MEDIA_LIST');
	
	if($page == 'meta') {
		
		backend::addSubNavi(lang::get('media'),	url::backend('meta', ['subpage'=>'media']), 'circle', -1, function() {
			return dir::addon('mediamanager', 'page'.DIRECTORY_SEPARATOR.'meta.media.php');
		});
		
	}
	
	if($page == 'media' && $subpage == 'files' && in_array($action, ['add', 'edit'])) {	
	
		extension::add('FORM_BEFORE_ACTION', function($form) {
			$form = metainfos::getMetaInfos($form, 'media');
		});
	
	}
		
}

?>