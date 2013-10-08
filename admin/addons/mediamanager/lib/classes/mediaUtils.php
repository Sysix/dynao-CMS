<?php

class mediaUtils {
	

	public static function fixFileName($name) {
		
		$name = mb_strtolower($name);
		
		$replace = array('ä'=>'ae', 'ö'=>'oe', 'ü'=>'ue', 'ß'=>'ss');
		$name = str_replace(array_keys($replace), array_values($replace), $name);
		
		$name = preg_replace('/[^a-z0-9._]/', '-', $name);
		
		return preg_replace('/[-]{2,}/', '-', $name);
		
	}
	
}

?>