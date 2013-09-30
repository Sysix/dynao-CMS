<?php

$files = scandir(dir::media());

foreach($files as $file) {

	if(in_array($file, array('..', '.')))
		continue;
		
	var_dump($file);
	
}

?>