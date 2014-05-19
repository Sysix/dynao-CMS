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

function filterValue($value) {
		
		$value = mb_strtolower($value);
	
		$search = ['ä', 'ü', 'ö', 'ß', '&'];
		$replace = ['ae', 'ue', 'oe', 'ss', 'und'];
		
		$value = str_replace($search, $replace, $value);
		
		$value = preg_replace('/[^a-z0-9]/', '-',  $value);
		
		return $value;
	
	}


?>