<?php

require(dir::addon('mediamanager', 'lib/classes/media.php'));

if(!dyn::has('extensions')) {
	
	$imageExtensions = array("gif", "jpeg", "jpg", "png", "bmp");
	$videoExtensions = array("3gp", "avi", "flv", "m4v", "mov", "mp4", "mpg", "wmv", "mkv", "mpeg");
	$audioExtensions = array("mp3", "wma", "m4a");
	
	dyn::add('extensions', array($imageExtensions, $videoExtensions, $audioExtensions), true);
	dyn::save();
	
}

?>