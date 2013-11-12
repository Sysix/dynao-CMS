<?php

require(dir::addon('mediamanager', 'lib/classes/media.php'));
require(dir::addon('mediamanager', 'lib/classes/mediaUtils.php'));
require(dir::addon('mediamanager', 'lib/classes/formMedia.php'));
require(dir::addon('mediamanager', 'lib/classes/formMediaList.php'));

backend::addNavi('Media', url::backend('media'), 'picture-o', 2);

form::addClassMethod('addMediaField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMedia');
	
});

form::addClassMethod('addMediaListField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMediaList');
	
});

?>