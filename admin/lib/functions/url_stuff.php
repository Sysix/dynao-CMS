<?php

// Klasen für URL Minipulationen
// Die Datei kann noch anders heißen und die Funktionen auch!

function url_addParam($key, $value) {

	$getArray = $_GET;

	if(is_string($key) && is_string($value)) {
		
		return http_build_query([$key=>$value] + $getArray);
		
	}
	
	// Wenn Key und Value ein Array
	
	if(is_array($key) && is_array($value)) {
		
		$newArray = array_combine($key, $value);
		
		return http_build_query($newArray + $getArray);
		
	}
	
	//throw new Exception();
	
}


?>