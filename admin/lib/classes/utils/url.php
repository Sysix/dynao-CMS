<?php

class url {
	
	public static function backend($page = '', $params = []) {		
		
		$return = 'index.php';
		
		if($page != '')			
			$return .= '?page='.$page;
		
		if(count($params)) {
			
			foreach($params as $key=>$val) {
				$return .= '&amp;'.$key.'='.$val;	
			}
			
		}
			
		return $return;
		
	}
	
	
}

?>